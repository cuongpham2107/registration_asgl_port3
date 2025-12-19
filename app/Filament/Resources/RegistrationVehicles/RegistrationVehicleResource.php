<?php

namespace App\Filament\Resources\RegistrationVehicles;

use App\Filament\Resources\RegistrationVehicles\Pages\CreateRegistrationVehicle;
use App\Filament\Resources\RegistrationVehicles\Pages\EditRegistrationVehicle;
use App\Filament\Resources\RegistrationVehicles\Pages\ListRegistrationVehicles;
use App\Filament\Resources\RegistrationVehicles\Schemas\RegistrationVehicleForm;
use App\Filament\Resources\RegistrationVehicles\Tables\RegistrationVehiclesTable;
use App\Models\RegistrationVehicle;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RegistrationVehicleResource extends Resource
{
    protected static ?string $model = RegistrationVehicle::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTruck;

    protected static ?string $navigationLabel = 'Đăng ký xe khai thác';

    protected static ?string $recordTitleAttribute = 'driver_name';

    protected static ?int $navigationSort = 2;
    public static function form(Schema $schema): Schema
    {
        return RegistrationVehicleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RegistrationVehiclesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRegistrationVehicles::route('/'),
            // 'create' => CreateRegistrationVehicle::route('/create'),
            // 'edit' => EditRegistrationVehicle::route('/{record}/edit'),
        ];
    }
}
