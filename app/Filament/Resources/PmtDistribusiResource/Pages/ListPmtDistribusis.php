<?php

namespace App\Filament\Resources\PmtDistribusiResource\Pages;

use App\Filament\Resources\PmtDistribusiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPmtDistribusis extends ListRecords
{
    protected static string $resource = PmtDistribusiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
