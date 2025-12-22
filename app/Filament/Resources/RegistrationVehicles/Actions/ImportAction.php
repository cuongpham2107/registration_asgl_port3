<?php

namespace App\Filament\Resources\RegistrationVehicles\Actions;

use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Notifications\Notification;

class ImportAction
{
    public static function make(): Action
    {
        return Action::make('import')
            ->button()
            ->color('info')
            ->size('sm')
            ->label('Nhập File danh sách xe')
            ->modalHeading('Import Danh sách xe từ File Excel')
            ->modalDescription(new \Illuminate\Support\HtmlString('File Excel phải đúng định dạng theo mẫu. Vui lòng tải về mẫu trước khi import. <br><a href="/template.xlsx" download class="text-primary-600 hover:underline font-semibold">📥 Tải file mẫu tại đây</a>'))
            ->icon('heroicon-s-inbox-arrow-down')
            ->form([
                FileUpload::make('file')
                    ->label('File danh sách xe')
                    ->acceptedFileTypes(['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                    ->required()
            ])
            ->action(function (array $data, Set $set, Get $get) {
                try {
                    $file = $data['file'];
                    $filePath = storage_path('app/public/' . $file);

                    // Đọc file Excel
                    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
                    $worksheet = $spreadsheet->getActiveSheet();
                    $rows = $worksheet->toArray();

                    // Bỏ qua dòng header (dòng đầu tiên)
                    array_shift($rows);

                    // Lấy dữ liệu vehicles hiện tại và lọc bỏ dòng trống
                    $currentVehicles = $get('registration_vehicles') ?? [];
                    $currentVehicles = array_filter($currentVehicles, function ($vehicle) {
                        // Giữ lại những dòng có ít nhất 1 field không rỗng
                        return !empty($vehicle['driver_name']) ||
                            !empty($vehicle['driver_id_card']) ||
                            !empty($vehicle['license_plate']) ||
                            !empty($vehicle['load_capacity']) ||
                            !empty($vehicle['entry_gate']) ||
                            !empty($vehicle['expected_arrival_time']) ||
                            !empty($vehicle['notes']);
                    });

                    // Chuyển đổi dữ liệu từ Excel
                    $importedVehicles = [];
                    foreach ($rows as $row) {
                        // Bỏ qua dòng trống
                        if (empty(array_filter($row))) {
                            continue;
                        }
                        $importedVehicles[] = [
                            'driver_name' => $row[0] ?? '',
                            'driver_id_card' => $row[1] ?? '',
                            'license_plate' => $row[2] ?? '',
                            'load_capacity' => $row[3] ?? '',
                            'entry_gate' => $row[4] ?? '',
                            'expected_arrival_time' => $row[5] ?? '',
                            'notes' => $row[6] ?? '',
                        ];
                    }

                    // Gộp dữ liệu cũ và mới
                    $allVehicles = array_merge($currentVehicles, $importedVehicles);

                    // Set lại dữ liệu vào TableRepeater
                    $set('registration_vehicles', $allVehicles);

                    Notification::make()
                        ->title('Import thành công')
                        ->success()
                        ->body('Đã thêm ' . count($importedVehicles) . ' xe vào danh sách')
                        ->send();

                } catch (\Exception $e) {
                    Notification::make()
                        ->title('Import thất bại')
                        ->danger()
                        ->body('Lỗi: ' . $e->getMessage())
                        ->send();
                }
            });
    }
}