<?php

namespace App\Filament\Resources\RegistrationVehicles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RegistrationVehiclesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('driver_name')
                    ->label('Tên lái xe')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('driver_id_card')
                    ->label('CMND/CCCD')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('license_plate')
                    ->label('Biển số xe')
                    ->searchable(),
                TextColumn::make('load_capacity')
                    ->label('Tải trọng')
                    ->searchable(),
                TextColumn::make('entry_gate')
                    ->label('Cổng vào')
                    ->searchable(),
                TextColumn::make('expected_arrival_time')
                    ->label('Thời gian dự kiến vào')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('company.name')
                    ->label('Thuộc đơn vị')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->sortable()
                    ->badge(),
                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Ngày cập nhật')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
