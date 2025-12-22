<?php

namespace App\Filament\Resources\RegistrationDirectlies\Tables;

use App\Filament\Resources\RegistrationDirectlies\Actions\EnteringAction;
use App\Filament\Resources\RegistrationDirectlies\Actions\ExitedAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class RegistrationDirectliesTable
{
    public static function configure(Table $table): Table
    {
        return $table

            ->columns([
                TextColumn::make('name')
                    ->label('Họ và tên')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('id_card')
                    ->label('CMND/CCCD')
                    ->searchable(),
                TextColumn::make('license_plate')
                    ->label('Biển số xe')
                    ->searchable(),
                TextColumn::make('load_capacity')
                    ->label('Tải trọng')
                    ->alignCenter()
                    ->formatStateUsing(fn ($state) => "{$state} tấn")
                    ->searchable(),
                TextColumn::make('entry_gate')
                    ->label('Cổng vào')
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('expected_arrival_time')
                    ->label('Giờ vào dự kiến')
                    ->dateTime('d/m/Y H:i')
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
                //
            ])
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
                EditAction::make()
                    ->label("")
                    ->button(),
                DeleteAction::make()
                    ->label("")
                    ->button(),
            ],position: RecordActionsPosition::BeforeColumns)
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
