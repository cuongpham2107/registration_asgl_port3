<?php

namespace App\Filament\Resources\LoadCapacities\Pages;

use App\Filament\Resources\LoadCapacities\LoadCapacityResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLoadCapacity extends EditRecord
{
    protected static string $resource = LoadCapacityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
