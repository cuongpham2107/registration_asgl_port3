<?php

namespace App\Filament\Resources\LoadCapacities\Pages;

use App\Filament\Resources\LoadCapacities\LoadCapacityResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLoadCapacities extends ListRecords
{
    protected static string $resource = LoadCapacityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tạo tải trọng mới')
                ->icon('heroicon-o-plus')
                ->slideOver(),
        ];
    }
}
