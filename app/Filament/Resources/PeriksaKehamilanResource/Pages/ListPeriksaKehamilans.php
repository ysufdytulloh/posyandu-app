<?php

namespace App\Filament\Resources\PeriksaKehamilanResource\Pages;

use App\Filament\Resources\PeriksaKehamilanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPeriksaKehamilans extends ListRecords
{
    protected static string $resource = PeriksaKehamilanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
