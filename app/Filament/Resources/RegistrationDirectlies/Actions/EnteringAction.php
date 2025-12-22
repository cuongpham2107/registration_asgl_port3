<?php

namespace App\Filament\Resources\RegistrationDirectlies\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class EnteringAction
{
    public static function make(): Action
    {
        return Action::make('entering')
            ->action(function ($record) {
                try {
                    $record->update([
                        'status' => 'entering',
                        'start_date' => now(),
                    ]);
                    Notification::make()
                        ->title('Cập nhật trạng thái xe vào cảng thành công.')
                        ->success()
                        ->send();
                } catch (\Exception $e) {
                    Notification::make()
                        ->title('Đã xảy ra lỗi trong quá trình cập nhật trạng thái xe vào cảng.')
                        ->danger()
                        ->send();
                    return;
                }
            });
    }
}