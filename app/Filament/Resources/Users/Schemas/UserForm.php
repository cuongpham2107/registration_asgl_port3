<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thông tin tài khoản')
                    ->columns([
                        'sm' => 1,
                        'md' => 2,
                        'lg' => 4,
                    ])
                    ->schema([
                        TextInput::make('name')
                            ->label('Họ và tên')
                            ->required()
                            ->columnSpan([
                                'sm' => 1,
                                'md' => 2,
                                'lg' => 2,
                            ]),
                        TextInput::make('email')
                            ->label('Địa chỉ Email')
                            ->prefixIcon('heroicon-o-envelope')
                            ->required()
                            ->columnSpan([
                                'sm' => 1,
                                'md' => 2,
                                'lg' => 2,
                            ]),
                        TextInput::make('mobile_phone')
                            ->label('Số điện thoại')
                            ->prefixIcon('heroicon-o-phone')
                            ->columnSpan([
                                'sm' => 1,
                                'md' => 1,
                                'lg' => 2,
                            ]),
                        TextInput::make('asgl_id')
                            ->label('Mã nhân viên ASGL')
                            ->columnSpan([
                                'sm' => 1,
                                'md' => 1,
                                'lg' => 2,
                            ]),
                        TextInput::make('username')
                            ->label('Tài khoản')
                            ->columnSpan([
                                'sm' => 1,
                                'md' => 1,
                                'lg' => 2,
                            ]),
                        TextInput::make('password')
                            ->label('Mật khẩu')
                            ->password()
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->minLength(8)
                            ->validationAttribute('mật khẩu')
                            ->columnSpan([
                                'sm' => 1,
                                'md' => 1,
                                'lg' => 2,
                            ]),

                        TextInput::make('department_name')
                            ->label('Phòng ban')
                            ->columnSpan([
                                'sm' => 1,
                                'md' => 2,
                                'lg' => 2,
                            ]),
                    ])
                    ->columnSpan(2),
                Grid::make(1)
                    ->schema([
                        Section::make('Phân quyền')
                            ->columns(1)
                            ->schema([
                                Select::make('roles')
                                    ->label('Quyền')
                                    ->relationship('roles', 'name')
                                    ->multiple()
                                    ->searchable(['name', 'email'])
                                    ->preload(),
                            ])
                            ->columnSpan('full')
                            ->hidden(fn () => ! auth()->user()?->hasRole('super_admin')),
                        Section::make('Hình đại diện')
                            ->schema([
                                ViewField::make('avatar')
                                    ->view('filament.forms.components.avatar')
                                    ->label(''),
                            ])
                            ->columnSpan('full'),
                    ])
                    ->columnSpan(1),
            ])->columns(3);
    }
}
