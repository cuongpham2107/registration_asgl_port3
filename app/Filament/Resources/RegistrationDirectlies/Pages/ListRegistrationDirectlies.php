<?php

namespace App\Filament\Resources\RegistrationDirectlies\Pages;

use App\Filament\Resources\RegistrationDirectlies\RegistrationDirectlyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRegistrationDirectlies extends ListRecords
{
    protected static string $resource = RegistrationDirectlyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Thêm xe ra vào')
                ->modalHeading('Thêm xe ra vào')
                ->slideOver(),
        ];
    }

    public function getHeading(): string
    {
        return "Danh sách xe ra vào";
    }
}
