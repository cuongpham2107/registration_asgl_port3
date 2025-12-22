<?php

namespace App\Filament\Resources\Gateways\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GatewayForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thông tin cổng')
                    ->description('Nhập thông tin cổng vào/ra')
                    ->columnSpanFull()
                    ->schema(components: [
                        TextInput::make('name')
                            ->label('Tên cổng')
                            ->required(),
                    ])
            ]);
    }
}
