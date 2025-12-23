<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                $user = auth()->user();

                // Nếu chưa login, return query rỗng
                if (! $user) {
                    return $query->whereRaw('1 = 0');
                }
                // Super admin và approve_vehicle thấy tất cả

                if ($user->hasRole('super_admin') || $user->hasRole('approve_vehicle')) {
                    return $query;
                }

                // User thường (panel_user, approver) không thấy gì
                return $query->whereRaw('1 = 0');
            })
            ->columns([
                ImageColumn::make('avatar')
                    ->label('Avatar')
                    ->width(40)
                    ->circular()
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Họ và tên')
                    ->searchable(),
                TextColumn::make('username')
                    ->label('Tài khoản')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Địa chỉ Email')
                    ->searchable(),
                TextColumn::make('department_name')
                    ->label('Phòng ban')
                    ->searchable(),
                TextColumn::make('roles.name')
                    ->label('Quyền')
                    ->badge()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime()
                    ->sortable(),
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
