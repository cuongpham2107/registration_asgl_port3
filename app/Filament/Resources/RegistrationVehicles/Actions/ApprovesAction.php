<?php

namespace App\Filament\Resources\RegistrationVehicles\Actions;

use App\Models\RegistrationDirectly;
use DB;
use Filament\Actions\BulkAction;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;

class ApprovesAction
{
    public static function make(): BulkAction
    {
        return BulkAction::make('approves')
            ->label('Duyệt')
            ->button()
            ->color('primary')
            ->icon('heroicon-o-check-circle')
            ->extraAttributes(['class' => ''])
            ->action(function (Collection $records) {
                try {
                    DB::transaction(function () use ($records) {
                    foreach ($records as $record) {
                        RegistrationDirectly::create([
                            'name' => $record->driver_name,
                            'id_card' => $record->driver_id_card,
                            'license_plate' => $record->license_plate,
                            'id_load_capacity' => $record->id_load_capacity,
                            'id_gateway' => $record->id_gateway,
                            'expected_arrival_time' => $record->expected_arrival_time,
                            'notes' => $record->notes,
                            'start_date' => null,
                            'end_date' => null,
                            'status' => 'waiting_entry',
                            'id_registration_vehicle' => $record->id,
                        ]);
                        $record->update([
                            'status' => 'approved',
                            'user_id' => auth()->id(),
                            'approval_date' => now(),
                        ]);
                    }
                    Notification::make()
                        ->title('Phê duyệt thành công.')
                        ->success()
                        ->send();
                });
                } catch (\Exception $e) {
                    Notification::make()
                        ->title('Đã xảy ra lỗi trong quá trình phê duyệt.')
                        ->danger()
                        ->send();
                    return;
                }
            });
    }
}