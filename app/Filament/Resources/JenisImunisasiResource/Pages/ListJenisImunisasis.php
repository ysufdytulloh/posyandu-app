<?php

namespace App\Filament\Resources\JenisImunisasiResource\Pages;

use App\Filament\Resources\JenisImunisasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJenisImunisasis extends ListRecords
{
    protected static string $resource = JenisImunisasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
