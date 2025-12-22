<?php

namespace App\Filament\Resources\RegistrationDirectlies\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ExitedAction
{
    public static function make(): Action
    {
        return Action::make('exited')
            ->action(function ($record) {
                try {
                    $record->update([
                        'status' => 'exited',
                        'end_date' => now(),
                    ]);
                    Notification::make()
                        ->title('Cập nhật trạng thái xe ra khỏi cảng thành công.')
                        ->success()
                        ->send();
                } catch (\Exception $e) {
                    Notification::make()
                        ->title('Đã xảy ra lỗi trong quá trình cập nhật trạng thái xe ra khỏi cảng.')
                        ->danger()
                        ->send();
                    return;
                }
            });
    }
}