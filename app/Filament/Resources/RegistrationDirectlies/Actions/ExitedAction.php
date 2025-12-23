<?php

namespace App\Filament\Resources\RegistrationDirectlies\Actions;

use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ExitedAction
{
    public static function make(): Action
    {
        return Action::make('exited')
            ->action(function ($record) {
                try {
                    // pass data as array to loadView

                    $record->update([
                        // 'status' => 'exited',
                        'end_date' => now(),
                    ]);

                    // Load relationship if not already loaded
                    $record->load('loadCapacity');

                    // Determine if daytime or nighttime based on start_date and end_date
                    // Daytime: 6:00 - 18:00, Nighttime: 18:00 - 6:00
                    $isDaytime = self::isDaytimePrice($record->start_date, $record->end_date);
                    $selectedPrice = $isDaytime
                        ? $record->loadCapacity->daytime_price
                        : $record->loadCapacity->nighttime_price;

                    // Calculate total operation hours
                    $totalHours = 0;
                    if ($record->start_date && $record->end_date) {
                        $start = \Carbon\Carbon::parse($record->start_date);
                        $end = \Carbon\Carbon::parse($record->end_date);

                        // Calculate difference in hours (use absolute value to handle any order)
                        $diffInMinutes = abs($start->diffInMinutes($end));
                        $totalHours = ceil($diffInMinutes / 60); // Round up to next hour for billing
                    }

                    $data = $record->toArray();
                    $data['is_daytime'] = $isDaytime;
                    $data['selected_price'] = $selectedPrice;
                    $data['total_hours'] = $totalHours;

                    // pass under a single key so the view can access the record array as $record
                    $pdf = Pdf::loadView('pdf.invoice', ['record' => $data]);

                    // Save PDF to storage
                    $filename = 'invoice-'.$record->name.'-'.$record->id_card.'-'.$record->license_plate.'-'.now()->format('YmdHis').'.pdf';
                    $path = 'invoices/'.$filename;
                    \Illuminate\Support\Facades\Storage::disk('public')->put($path, $pdf->output());

                    // Get the public URL for the file
                    $url = \Illuminate\Support\Facades\Storage::disk('public')->url($path);

                    Notification::make()
                        ->title('Cập nhật trạng thái xe ra khỏi cảng thành công.')
                        ->success()
                        ->body('File hoá đơn đã được tạo. [Tải xuống]('.$url.')')
                        ->duration(5000)
                        ->send();

                    // Trigger download
                    return response()->streamDownload(function () use ($pdf) {
                        echo $pdf->output();
                    }, $filename);
                } catch (\Exception $e) {
                    report($e);
                    Notification::make()
                        ->title('Đã xảy ra lỗi trong quá trình tạo hoá đơn / cập nhật trạng thái.')
                        ->danger()
                        ->body($e->getMessage())
                        ->send();

                    return;
                }
            });
    }

    /**
     * Determine if the price should be daytime or nighttime
     * Daytime: 6:00 - 18:00
     * Nighttime: 18:00 - 6:00
     */
    private static function isDaytimePrice($startDate, $endDate): bool
    {
        $start = \Carbon\Carbon::parse($startDate);
        $end = $endDate ? \Carbon\Carbon::parse($endDate) : now();

        // Check if either start or end time is during nighttime
        $startHour = (int) $start->format('H');
        $endHour = (int) $end->format('H');

        // If start time is during nighttime (18:00 - 6:00)
        if ($startHour >= 18 || $startHour < 6) {
            return false;
        }

        // If end time is during nighttime (18:00 - 6:00)
        if ($endHour >= 18 || $endHour < 6) {
            return false;
        }

        // Both start and end are during daytime
        return true;
    }
}
