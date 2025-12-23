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
                ->label('Thêm xe vào kiểm hoá')
                ->modalHeading('Thêm xe vào kiểm hoá')
                ->slideOver(),
        ];
    }

    public function getHeading(): string
    {
        return 'Danh sách xe vào kiểm hoá';
    }
}
