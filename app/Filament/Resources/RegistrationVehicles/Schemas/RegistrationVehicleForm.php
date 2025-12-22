<?php

namespace App\Filament\Resources\RegistrationVehicles\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RegistrationVehicleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thông tin đăng ký xe khai thác')
                    ->description('Nhập thông tin đăng ký xe khai thác')
                    ->columnSpanFull()
                    ->schema(components: [
                        TextInput::make('driver_name')
                            ->label('Tên lái xe')
                            ->required()
                            ->columnSpan(3),
                        TextInput::make('driver_id_card')
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
                        Select::make('company_id')
                            ->label('Thuộc đơn vị')
                            ->relationship('company', 'name')
                            ->required()
                            ->columnSpan(2),
                        Select::make('status')
                            ->label('Trạng thái')
                            ->options([
                                'pending_approval' => 'Cần phê duyệt',
                                'entered' => 'Đã vào',
                                'exited' => 'Đã ra',
                                'approved' => 'Đã phê duyệt',
                                'rejected' => 'Đã từ chối',
                            ])
                            ->default('pending_approval')
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->columnSpan(2),
                        Textarea::make('notes')
                            ->label('Ghi chú')
                            ->columnSpanFull(),

                    ])->columns(6),
            ]);
    }
}
