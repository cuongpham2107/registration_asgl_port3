<?php

namespace App\Filament\Resources\RegistrationVehicles\Filters;

use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Illuminate\Database\Eloquent\Builder;

class ListFilters
{
    public static function make(): Filter
    {
        return Filter::make('registration_vehicle_filters')
            ->schema([
                TextInput::make('search')
                    ->label('Tìm kiếm')
                    ->placeholder('Tên lái xe, CMND/CCCD, biển số xe...'),
                Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'pending_approval' => 'Chờ duyệt',
                        'approved' => 'Đã duyệt',
                        'entered' => 'Đã vào',
                        'exited' => 'Đã ra',
                        'rejected' => 'Từ chối',
                    ])
                    ->default('pending_approval'),
                DatePicker::make('filter_start_date')
                    ->label('Từ ngày')
                    ->placeholder('Chọn ngày bắt đầu')
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->default(now()),
                DatePicker::make('filter_end_date')
                    ->label('Đến ngày')
                    ->placeholder('Chọn ngày kết thúc')
                    ->native(false)
                    ->displayFormat('d/m/Y'),
            ])
            ->columns(4)
            ->columnSpanFull()
            ->query(function (Builder $query, array $data): Builder {
                return $query
                    ->when(
                        $data['search'] ?? null,
                        fn (Builder $query, $search): Builder => $query->where(function ($query) use ($search) {
                            return $query->where('driver_name', 'like', "%{$search}%")
                                ->orWhere('driver_id_card', 'like', "%{$search}%")
                                ->orWhere('license_plate', 'like', "%{$search}%")
                                ->orWhere('notes', 'like', "%{$search}%");
                        }),
                    )
                    ->when(
                        $data['status'] ?? null,
                        fn (Builder $query, $status): Builder => $query->where('status', $status),
                    )
                    ->when(
                        ($data['filter_start_date'] ?? null) && ! ($data['filter_end_date'] ?? null),
                        fn (Builder $query) => $query->whereDate('expected_arrival_time', Carbon::parse($data['filter_start_date'], 'Asia/Ho_Chi_Minh'))
                    )
                    ->when(
                        ($data['filter_start_date'] ?? null) && ($data['filter_end_date'] ?? null),
                        fn (Builder $query) => $query->whereBetween(
                            'expected_arrival_time',
                            [
                                Carbon::parse($data['filter_start_date'], 'Asia/Ho_Chi_Minh')->startOfDay(),
                                Carbon::parse($data['filter_end_date'], 'Asia/Ho_Chi_Minh')->endOfDay(),
                            ]
                        )
                    );
            })
            ->indicateUsing(function (array $data): array {
                $indicators = [];

                if ($data['search'] ?? null) {
                    $indicators[] = Indicator::make('Tìm kiếm: '.$data['search'])
                        ->removeField('search');
                }

                if ($data['status'] ?? null) {
                    $statusText = match ($data['status']) {
                        'pending_approval' => 'Chờ duyệt',
                        'approved' => 'Đã duyệt',
                        'entered' => 'Đã vào',
                        'exited' => 'Đã ra',
                        'rejected' => 'Từ chối',
                        default => $data['status'],
                    };
                    $indicators[] = Indicator::make('Trạng thái: '.$statusText)
                        ->removeField('status');
                }

                if ($data['filter_start_date'] ?? null) {
                    $indicators[] = Indicator::make('Từ ngày: '.Carbon::parse($data['filter_start_date'], 'Asia/Ho_Chi_Minh')->format('d/m/Y'))
                        ->removeField('filter_start_date');
                }

                if ($data['filter_end_date'] ?? null) {
                    $indicators[] = Indicator::make('Đến ngày: '.Carbon::parse($data['filter_end_date'], 'Asia/Ho_Chi_Minh')->format('d/m/Y'))
                        ->removeField('filter_end_date');
                }

                return $indicators;
            });
    }
}
