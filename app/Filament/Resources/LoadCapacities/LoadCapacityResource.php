<?php

namespace App\Filament\Resources\LoadCapacities;

use App\Filament\Resources\LoadCapacities\Pages\CreateLoadCapacity;
use App\Filament\Resources\LoadCapacities\Pages\EditLoadCapacity;
use App\Filament\Resources\LoadCapacities\Pages\ListLoadCapacities;
use App\Filament\Resources\LoadCapacities\Schemas\LoadCapacityForm;
use App\Filament\Resources\LoadCapacities\Tables\LoadCapacitiesTable;
use App\Models\LoadCapacity;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LoadCapacityResource extends Resource
{
    protected static ?string $model = LoadCapacity::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return LoadCapacityForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LoadCapacitiesTable::configure($table);
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
            'index' => ListLoadCapacities::route('/'),
            // 'create' => CreateLoadCapacity::route('/create'),
            // 'edit' => EditLoadCapacity::route('/{record}/edit'),
        ];
    }
}
