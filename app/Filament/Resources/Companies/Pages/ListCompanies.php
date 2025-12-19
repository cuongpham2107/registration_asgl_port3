<?php

namespace App\Filament\Resources\Companies\Pages;

use App\Filament\Resources\Companies\CompanyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCompanies extends ListRecords
{
    protected static string $resource = CompanyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Thêm đơn vị')
                ->modalHeading('Thêm đơn vị')
                ->slideOver(),
        ];
    }

    public function getHeading(): string
    {
        return "Danh sách đơn vị";
    }
}
