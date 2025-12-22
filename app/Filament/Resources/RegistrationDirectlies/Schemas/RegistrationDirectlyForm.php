<?php

namespace App\Filament\Resources\RegistrationDirectlies\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class RegistrationDirectlyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thông tin')
                    ->description('Nhập thông tin xe ra vào') 
                    ->columnSpanFull()
                    ->schema(components: [
                        TextInput::make('name')
                            ->label('Họ và tên')
                            ->required()
                            ->columnSpan(3),
                        TextInput::make('id_card')
                            ->label('CMND/CCCD')
                            ->required()
                            ->columnSpan(3),
                        TextInput::make('license_plate')
                            ->label('Biển số xe')
                            ->required()
                            ->columnSpan(2),
                        Select::make('id_load_capacity')
                            ->label('Tải trọng')
                            ->relationship('loadCapacity', 'name')
                            ->required()
                            ->columnSpan(2),
                        Select::make('id_gateway')
                            ->label('Cổng vào')
                            ->relationship('gateway', 'name')
                            ->columnSpan(2),
                        DateTimePicker::make('expected_arrival_time')
                            ->label('Thời gian dự kiến vào')
                            ->format('d/m/Y H:i')
                            ->seconds(false)
                            ->required()
                            ->columnSpan(2),
                       
                        DateTimePicker::make('start_date')
                            ->label('Giờ vào')
                            ->format('d/m/Y H:i')
                            ->seconds(false)
                            ->required()
                            ->columnSpan(2),
                        DateTimePicker::make('end_date')
                            ->label('Giờ ra')
                            ->format('d/m/Y H:i')
                            ->seconds(false)
                            ->required()
                            ->columnSpan(2),
                        // Select::make('status')
                        //     ->label('Trạng thái')
                        //     ->options(
                        //         [
                        //             'waiting_entry' => 'Chờ vào',
                        //             'entering' => 'Đang vào',
                        //             'exited' => 'Đã ra',
                        //         ]
                        //     )
                        //     ->required(),
                        // TextInput::make('id_registration_vehicle')
                        //     ->label('ID đăng ký xe')
                        //     ->numeric(),
                        Textarea::make('notes')
                            ->label('Ghi chú')
                            ->columnSpanFull(),
                    ])->columns(6)
            ]);
    }
}
