<?php

namespace App\Filament\Resources\RegistrationVehicles\Actions;

use App\Models\Gateway;
use App\Models\LoadCapacity;
use Filament\Actions\Action;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date as PhpSpreadsheetDate;
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
                    $file = $data['file'] ?? null;

                    // Resolve file path for different file representations:
                    // - Livewire TemporaryUploadedFile / UploadedFile
                    // - string path stored on 'public' disk (e.g. "01KD...")
                    // - full filesystem path
                    if ($file instanceof \Livewire\TemporaryUploadedFile || $file instanceof \Illuminate\Http\UploadedFile) {
                        $filePath = $file->getRealPath();
                    } elseif (is_string($file)) {
                        // Prefer public disk first
                        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($file)) {
                            $filePath = \Illuminate\Support\Facades\Storage::disk('public')->path($file);
                        } elseif (\Illuminate\Support\Facades\Storage::exists($file)) {
                            $filePath = \Illuminate\Support\Facades\Storage::path($file);
                        } else {
                            // Try common storage locations
                            $candidate = storage_path('app/public/' . ltrim($file, '\/'));
                            if (file_exists($candidate)) {
                                $filePath = $candidate;
                            } elseif (file_exists($file)) {
                                $filePath = $file;
                            } else {
                                throw new \Exception('File "' . $file . '" does not exist.');
                            }
                        }
                    } else {
                        throw new \Exception('No valid file found to import.');
                    }

                    // Read Excel file
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
                    
                    // Preload reference lists to avoid per-row queries
                    $loadCapacityList = LoadCapacity::all()->mapWithKeys(fn($m) => [$m->id => $m->name])->toArray();
                    $gatewayList = Gateway::all()->mapWithKeys(fn($m) => [$m->id => $m->name])->toArray();

                    // helper: normalize string to options array
                    $normalizeToOptions = function (string $s): array {
                        $s = trim(mb_strtolower($s));
                        $s = str_replace(["’", "‘", "“", "”", "—"], "'", $s);
                        // replace conjunctions with commas
                        $s = preg_replace('/\s+(hoặc|và|or|and)\s+/ui', ',', $s);
                        $s = str_replace([';', '/', '\\', ' - '], ',', $s);
                        $s = preg_replace('/,+/', ',', $s);
                        $s = preg_replace('/\s+/', ' ', $s);
                        $s = trim($s, " ,");
                        if ($s === '') {
                            return [];
                        }
                        return array_map('trim', array_filter(explode(',', $s), fn($v) => $v !== ''));
                    };

                    // Build normalized options map for lookups
                    $loadCapacityOptionsById = [];
                    foreach ($loadCapacityList as $id => $name) {
                        $loadCapacityOptionsById[$id] = self::normalizeToOptions((string) $name);
                    }
                    $gatewayOptionsById = [];
                    foreach ($gatewayList as $id => $name) {
                        $gatewayOptionsById[$id] = self::normalizeToOptions((string) $name);
                    }

                    $findMatchingId = function (array $excelOptions, array $dbOptionsById) {
                        if (empty($excelOptions)) {
                            return null;
                        }
                        $excelJoined = implode('|', $excelOptions);
                        // exact full-string match
                        foreach ($dbOptionsById as $id => $opts) {
                            if ($excelJoined === implode('|', $opts)) {
                                return $id;
                            }
                        }
                        // fallback: any intersection
                        foreach ($dbOptionsById as $id => $opts) {
                            if (count(array_intersect($excelOptions, $opts)) > 0) {
                                return $id;
                            }
                        }
                        return null;
                    };

                    $unmatched = [];
                    foreach ($rows as $index => $row) {
                        // Skip completely empty rows
                        if (empty(array_filter($row))) {
                            continue;
                        }

                        // Normalize input from Excel and convert to option tokens
                        $loadCapacityName = (string) ($row[3] ?? '');
                        $gatewayName = (string) ($row[4] ?? '');
                        $excelLoadOptions = self::normalizeToOptions($loadCapacityName);
                        $excelGatewayOptions = self::normalizeToOptions($gatewayName);

                        $id_load_capacity = self::findMatchingId($excelLoadOptions, $loadCapacityOptionsById);
                        $id_gateway = self::findMatchingId($excelGatewayOptions, $gatewayOptionsById);

                        if (! $id_load_capacity || ! $id_gateway) {
                            $unmatched[] = [
                                'row' => $index + 2, // +2 because header removed and rows are 0-based
                                'load_capacity' => $loadCapacityName,
                                'gateway' => $gatewayName,
                            ];
                        }
                        // dd($row[5]);
                        $importedVehicles[] = [
                            'driver_name' => $row[0] ?? '',
                            'driver_id_card' => $row[1] ?? '',
                            'license_plate' => $row[2] ?? '',
                            'id_load_capacity' => $id_load_capacity,
                            'id_gateway' => $id_gateway,
                            'expected_arrival_time' => $row[5] ?? '',
                            'notes' => $row[6] ?? '',
                        ];
                    }

                    // Gộp dữ liệu cũ và mới
                    $allVehicles = array_merge($currentVehicles, $importedVehicles);

                    // Set lại dữ liệu vào TableRepeater
                    $set('registration_vehicles', $allVehicles);

                    // Always notify success for rows processed
                    Notification::make()
                        ->title('Import hoàn tất')
                        ->success()
                        ->body('Đã thêm ' . count($importedVehicles) . ' xe vào danh sách')
                        ->send();

                    // If some rows had unmatched lookups, warn the user with row numbers
                    if (! empty($unmatched)) {
                        $rows = collect($unmatched)->pluck('row')->take(10)->implode(', ');
                        Notification::make()
                            ->title('Cảnh báo dữ liệu không khớp')
                            ->warning()
                            ->body('Có ' . count($unmatched) . " dòng có giá trị LoadCapacity/Gateway không khớp (ví dụ hàng: $rows). Vui lòng kiểm tra file Excel.")
                            ->send();
                    }

                } catch (\Exception $e) {
                    Notification::make()
                        ->title('Import thất bại')
                        ->danger()
                        ->body('Lỗi: ' . $e->getMessage())
                        ->send();
                }
            });
    }

    /**
     * Normalize a string to an array of option tokens used for fuzzy matching.
     */
    private static function normalizeToOptions(string $s): array
    {
        $s = trim(mb_strtolower($s));
        $s = str_replace(["’", "‘", "“", "”", "—"], "'", $s);
        $s = preg_replace('/\s+(hoặc|và|or|and)\s+/ui', ',', $s);
        $s = str_replace([';', '/', '\\', ' - '], ',', $s);
        $s = preg_replace('/,+/', ',', $s);
        $s = preg_replace('/\s+/', ' ', $s);
        $s = trim($s, " ,");
        if ($s === '') {
            return [];
        }
        return array_map('trim', array_filter(explode(',', $s), fn($v) => $v !== ''));
    }

    /**
     * Find matching id from DB options by exact normalized match or token intersection.
     * Returns null when no match.
     */
    private static function findMatchingId(array $excelOptions, array $dbOptionsById): ?int
    {
        if (empty($excelOptions)) {
            return null;
        }

        $excelJoined = implode('|', $excelOptions);
        foreach ($dbOptionsById as $id => $opts) {
            if ($excelJoined === implode('|', $opts)) {
                return (int) $id;
            }
        }

        foreach ($dbOptionsById as $id => $opts) {
            if (count(array_intersect($excelOptions, $opts)) > 0) {
                return (int) $id;
            }
        }

        return null;
    }
}