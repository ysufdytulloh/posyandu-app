<?php

namespace App\Filament\Resources\SdidtkResource\Pages;

use App\Filament\Resources\SdidtkResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSdidtks extends ListRecords
{
    protected static string $resource = SdidtkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
