<?php

namespace App\Filament\Resources\ObatCacingResource\Pages;

use App\Filament\Resources\ObatCacingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListObatCacings extends ListRecords
{
    protected static string $resource = ObatCacingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
