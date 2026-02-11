<?php

namespace App\Filament\Pages;

use App\Filament\Resources\Users\Schemas\UserForm;
use App\Models\User;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ManageUser extends Page implements HasSchemas
{
     use InteractsWithSchemas;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationLabel = 'Thông tin cá nhân';

    protected static ?string $title = 'Quản lý thông tin cá nhân';

    protected string $view = 'filament.pages.manage-user';
    
    protected static ?int $navigationSort = 6;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(auth()->user()->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return UserForm::configure($schema)
            ->record($this->getRecord())
            ->statePath('data');
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();

            $record = $this->getRecord();
            if (! $record) {
                throw ValidationException::withMessages([
                    'data.username' => __('filament-panels::pages/auth/login.messages.failed'),
                ]);
            }
            $record->fill($data);
            $record->save();
            
            $this->form->record($record)->saveRelationships();

            Notification::make()
                ->success()
                ->title('Cập nhật thông tin thành công!')
                ->send();
        } catch (\Exception $exception) {
            Notification::make()
                ->danger()
                ->title('Cập nhật thông tin thất bại!')
                ->body($exception->getMessage())
                ->send();
        }
    }
    public function getRecord(): ?User
    {
        return Auth::user();
    }

    public static function canAccess(): bool
    {
        return ! auth()->user()?->hasRole('super_admin');
    }
}
