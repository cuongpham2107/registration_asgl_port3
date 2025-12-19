<?php

namespace App\Filament\Resources\Companies\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CompanyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thông tin đơn vị')
                ->description('Nhập thông tin đơn vị cung cấp')
                ->columnSpanFull()
                ->columns(6)
                    ->schema(components: [
                         TextInput::make('name')
                            ->label('Tên đơn vị')
                            ->required()
                            ->columnSpan(3),
                        TextInput::make('tax_number')
                            ->label('Mã số thuế')
                            ->required()
                            ->columnSpan(3),
                        TextInput::make('address')
                            ->label('Địa chỉ')
                            ->required()
                            ->columnSpan(2),
                        TextInput::make('phone_number')
                            ->label('Số điện thoại')
                            ->tel()
                            ->required()
                            ->columnSpan(2),
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->columnSpan(2),
                    ]),
               
            ]);
    }
}
