<?php

namespace App\Filament\Pages;

use App\Models\User;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Facades\Filament;
use Filament\Schemas\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class Login extends BaseLogin
{
    /**
     * Ghi đè phần login để login bằng api của ASGL
     */
    public function authenticate(): ?LoginResponse
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return null;
        }

        $data = $this->form->getState();
        // dd(Filament::auth()->attempt($this->getCredentialsFromFormData($data)));
        // Thử login bằng tài khoản trong database trước
        if (! Filament::auth()->attempt($this->getCredentialsFromFormData($data), $data['remember'] ?? false)) {
            
            // Nếu login local thất bại, thử login bằng api của ASGL
            $loginAsgl = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post('https://id.asgl.net.vn/api/auth/login', [
                'login' => $data['username'],
                'password' => $data['password'],
            ]);
            
            // Ensure we are working with a Response object, not a Promise
            if ($loginAsgl instanceof \GuzzleHttp\Promise\PromiseInterface) {
                $loginAsgl = $loginAsgl->wait();
            }

            if (! $loginAsgl->successful()) {
                $this->throwFailureValidationException();
            }

            $userResponse = $loginAsgl->json()['data']['user'];

            // Tìm user dựa trên asgl_id hoặc username
            $user = User::where('asgl_id', $userResponse['id'])
                ->orWhere('username', $userResponse['username'])
                ->first();

            if ($user) {
                // Cập nhật thông tin user hiện tại
                $user->update([
                    'name' => $userResponse['full_name'],
                    'username' => $userResponse['username'],
                    'mobile_phone' => $userResponse['mobile_phone'],
                    'asgl_id' => $userResponse['id'],
                    'avatar' => $userResponse['avatar'],
                    'department_name' => $userResponse['positions'][0]['department']['short_code'] ?? null,
                ]);
            } else {
                // Tạo user mới nếu chưa tồn tại
                $user = User::create([
                    'name' => $userResponse['full_name'],
                    'username' => $userResponse['username'],
                    'mobile_phone' => $userResponse['mobile_phone'],
                    'asgl_id' => $userResponse['id'],
                    'avatar' => $userResponse['avatar'],
                    'email' => $userResponse['email'] ?? $userResponse['username'],
                    'password' => Str::password(),
                    'department_name' => $userResponse['positions'][0]['department']['short_code'] ?? null,
                ]);
                
                // KHÔNG tự động gán role - Admin sẽ gán role thủ công
            }
            Filament::auth()->login($user, $data['remember'] ?? false);
        }

        $user = Filament::auth()->user();

        if (
            ($user instanceof FilamentUser) &&
            (! $user->canAccessPanel(Filament::getCurrentPanel()))
        ) {
            Filament::auth()->logout();

            $this->throwFailureValidationException();
        }

        session()->regenerate();

        return app(LoginResponse::class);
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('username')
            ->label('Tên đăng nhập')
            ->required()
            ->autocomplete('username')
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1]);
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.username' => __('filament-panels::pages/auth/login.messages.failed'),
        ]);
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'username' => $data['username'],
            'password' => $data['password'],
        ];
    }
}