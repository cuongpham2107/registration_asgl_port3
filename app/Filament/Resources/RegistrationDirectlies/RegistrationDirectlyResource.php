<?php

namespace App\Filament\Resources\RegistrationDirectlies;

use App\Filament\Resources\RegistrationDirectlies\Pages\CreateRegistrationDirectly;
use App\Filament\Resources\RegistrationDirectlies\Pages\EditRegistrationDirectly;
use App\Filament\Resources\RegistrationDirectlies\Pages\ListRegistrationDirectlies;
use App\Filament\Resources\RegistrationDirectlies\Schemas\RegistrationDirectlyForm;
use App\Filament\Resources\RegistrationDirectlies\Tables\RegistrationDirectliesTable;
use App\Models\RegistrationDirectly;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RegistrationDirectlyResource extends Resource
{
    protected static ?string $model = RegistrationDirectly::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $navigationLabel = 'Danh sách ra vào';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return RegistrationDirectlyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RegistrationDirectliesTable::configure($table);
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
            'index' => ListRegistrationDirectlies::route('/'),
            // 'create' => CreateRegistrationDirectly::route('/create'),
            // 'edit' => EditRegistrationDirectly::route('/{record}/edit'),
        ];
    }
}
