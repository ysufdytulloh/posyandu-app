<?php

namespace App\Filament\Resources\VitaminAResource\Pages;

use App\Filament\Resources\VitaminAResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVitaminAS extends ListRecords
{
    protected static string $resource = VitaminAResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
