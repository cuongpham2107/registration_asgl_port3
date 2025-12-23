<?php

namespace App\Filament\Resources\RegistrationVehicles\Pages;

use App\Filament\Resources\RegistrationVehicles\RegistrationVehicleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRegistrationVehicles extends ListRecords
{
    protected static string $resource = RegistrationVehicleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Thêm đăng ký xe kiểm hoá')
                ->icon('heroicon-o-plus')
                ->modalHeading('Thêm đăng ký xe kiểm hoá')
                ->slideOver(),
        ];
    }

    public function getHeading(): string
    {
        return 'Danh sách đăng ký xe kiểm hoá';
    }
}
