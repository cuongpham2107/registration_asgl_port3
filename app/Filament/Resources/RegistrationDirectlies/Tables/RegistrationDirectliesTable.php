<?php

namespace App\Filament\Resources\RegistrationDirectlies\Tables;

use App\Filament\Resources\RegistrationDirectlies\Actions\EnteringAction;
use App\Filament\Resources\RegistrationDirectlies\Actions\ExitedAction;
use App\Filament\Resources\RegistrationDirectlies\Filters\ListFilters;
use App\Models\RegistrationDirectly;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\Size;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RegistrationDirectliesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->emptyStateHeading('Không có kiểm hoá nào')
            ->emptyStateDescription('Hiện tại chưa có kiểm hoá nào.')
            ->modifyQueryUsing(fn (Builder $query) => $query->orderByRaw("CASE 
                WHEN status = 'waiting_entry' THEN 1 
                WHEN status = 'entering' THEN 2 
                WHEN status = 'exited' THEN 3 
                ELSE 4 
            END"))
            ->columns([
                TextColumn::make('name')
                    ->label('Họ và tên')
                    ->formatStateUsing(
                        fn (RegistrationDirectly $record): string => isset(explode('|', $record->name)[0]) ? trim(explode('|', mb_convert_case($record->name, MB_CASE_TITLE, 'UTF-8'))[0]) : mb_convert_case($record->name, MB_CASE_TITLE, 'UTF-8')
                    )
                    ->weight(FontWeight::Bold)
                    ->sortable()
                    ->searchable(),
                TextColumn::make('id_card')
                    ->label('CMND/CCCD')
                    ->alignCenter()
                    ->weight(FontWeight::Bold)
                    ->searchable(),
                TextColumn::make('license_plate')
                    ->label('Biển số xe')
                    ->alignCenter()
                    ->weight(FontWeight::Bold)
                    ->searchable(),
                TextColumn::make('loadCapacity.name')
                    ->label('Tải trọng')
                    ->alignCenter()
                    ->formatStateUsing(fn ($state) => "{$state} tấn")
                    ->searchable(),
                TextColumn::make('gateway.name')
                    ->label('Cổng vào')
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('expected_arrival_time')
                    ->label('Ngày vào dự kiến')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->width('150px')
                    ->alignCenter(),
                ColumnGroup::make('Thời gian ra vào', [
                    TextColumn::make('start_date')
                        ->label('Giờ vào')
                        ->dateTime('d/m/Y H:i')
                        ->sortable()
                        ->width('150px')
                        ->alignCenter(),
                    TextColumn::make('end_date')
                        ->label('Giờ ra')
                        ->dateTime('d/m/Y H:i')
                        ->sortable()
                        ->width('150px')
                        ->alignCenter(),
                ]),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->sortable()
                    ->alignCenter()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'waiting_entry' => 'Chờ vào',
                        'entering' => 'Đang vào',
                        'exited' => 'Đã ra',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'waiting_entry' => 'warning',
                        'entering' => 'info',
                        'exited' => 'success',
                        default => 'primary',
                    })
                    ->badge(),
                // TextColumn::make('id_registration_vehicle')
                //     ->label('ID đăng ký xe')
                //     ->numeric()
                //     ->sortable(),
                TextColumn::make('created_at')
                    ->label('Tạo lúc')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                ListFilters::make(),
            ], layout: FiltersLayout::AboveContent)
            // ->persistFiltersInSession()
            ->deferFilters(false)
            ->recordActions([
                EnteringAction::make()
                    ->label('Vào')
                    ->color('info')
                    ->hidden(fn ($record) => ($record?->status ?? null) !== 'waiting_entry')
                    ->button()
                    ->size('xs'),
                ExitedAction::make()
                    ->label('Ra')
                    ->color('danger')
                    ->hidden(fn ($record) => ($record?->status ?? null) !== 'entering')
                    ->button(),
                ActionGroup::make([
                    EditAction::make()
                        ->slideOver(),
                    DeleteAction::make(),
                ])->icon('heroicon-m-adjustments-vertical')
                    ->size(Size::Small)
                    ->iconButton()
                    ->color('gray')->link()->label(''),
            ], position: RecordActionsPosition::BeforeColumns)
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
