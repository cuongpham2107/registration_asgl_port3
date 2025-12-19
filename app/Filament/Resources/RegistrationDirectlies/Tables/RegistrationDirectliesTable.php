<?php

namespace App\Filament\Resources\RegistrationDirectlies\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
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
                    ->searchable(),
                TextColumn::make('entry_gate')
                    ->label('Cổng vào') 
                    ->searchable(),
                TextColumn::make('start_date')
                    ->label('Giờ vào')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('end_date')
                    ->label('Giờ ra')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'waiting_entry' => 'Chờ vào',
                        'entering' => 'Đang vào',
                        'exited' => 'Đã ra',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'waiting_entry' => 'yellow',
                        'entering' => 'blue',
                        'exited' => 'green',
                        default => 'gray',
                    })
                    ->badge(),
                TextColumn::make('id_registration_vehicle')
                    ->label('ID đăng ký xe')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
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
