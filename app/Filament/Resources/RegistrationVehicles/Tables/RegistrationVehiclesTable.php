<?php

namespace App\Filament\Resources\RegistrationVehicles\Tables;

use App\Filament\Resources\RegistrationVehicles\Actions\ApprovesAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Grouping\Group;

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
                    ->label('Thời gian dự kiến vào')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('company.name')
                    ->label('Thuộc đơn vị')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->sortable()
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'pending_approval' => 'Chờ duyệt',
                            'approved' => 'Đã duyệt',
                            'entered' => 'Đã vào',
                            'exited' => 'Đã ra',
                            'rejected' => 'Từ chối',
                            default => $state,
                        };
                    })
                    ->color(function ($state) {
                        return match ($state) {
                            'pending_approval' => 'warning',
                            'approved' => 'info',
                            'entered' => 'success',
                            'exited' => 'danger',
                            'rejected' => 'gray',
                            default => 'primary',
                        };
                    })
                    ->alignCenter()
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
            ->groups([
            Group::make('company.name')
                    ->label('Đơn vị')
                    ->titlePrefixedWithLabel(false)
                    ->collapsible(),
            ])
            ->defaultGroup('company.name')
            ->recordActions([
                EditAction::make()
                    ->iconButton(),
                DeleteAction::make()
                    ->iconButton(),
            ],position: RecordActionsPosition::BeforeColumns)
            ->toolbarActions([
                ApprovesAction::make(),
                DeleteBulkAction::make()
            ]);
    }
}
