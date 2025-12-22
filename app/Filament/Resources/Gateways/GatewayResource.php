<?php

namespace App\Filament\Resources\Gateways;

use App\Filament\Resources\Gateways\Pages\CreateGateway;
use App\Filament\Resources\Gateways\Pages\EditGateway;
use App\Filament\Resources\Gateways\Pages\ListGateways;
use App\Filament\Resources\Gateways\Schemas\GatewayForm;
use App\Filament\Resources\Gateways\Tables\GatewaysTable;
use App\Models\Gateway;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use App\Filament\Enums\NavigationGroup;
use Filament\Tables\Table;

class GatewayResource extends Resource
{
    protected static ?string $model = Gateway::class;

    protected static ?string $navigationLabel = 'Cổng';

    protected static string | UnitEnum | null $navigationGroup = NavigationGroup::DANH_MUC;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return GatewayForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GatewaysTable::configure($table);
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
            'index' => ListGateways::route('/'),
            // 'create' => CreateGateway::route('/create'),
            // 'edit' => EditGateway::route('/{record}/edit'),
        ];
    }
}
