<?php

namespace App\Filament\Resources\RegistrationDirectlies\Pages;

use App\Filament\Resources\RegistrationDirectlies\RegistrationDirectlyResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRegistrationDirectly extends EditRecord
{
    protected static string $resource = RegistrationDirectlyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
