<?php

namespace App\Filament\Resources\LoadCapacities\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LoadCapacityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thông tin tải trọng')
                    ->description('Nhập thông tin tải trọng')
                    ->columnSpanFull()
                    ->schema(components: [
                        TextInput::make('name')
                    ->label('Tên tải trọng')
                    ->required(),
                TextInput::make('daytime_price')
                    ->label('Giá vé ban ngày')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('nighttime_price')
                    ->label('Giá vé ban đêm')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                    ])
            ]);
    }
}
