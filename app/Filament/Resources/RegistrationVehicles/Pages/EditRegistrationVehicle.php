<?php

namespace App\Filament\Resources\RegistrationVehicles\Pages;

use App\Filament\Resources\RegistrationVehicles\RegistrationVehicleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRegistrationVehicle extends EditRecord
{
    protected static string $resource = RegistrationVehicleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
