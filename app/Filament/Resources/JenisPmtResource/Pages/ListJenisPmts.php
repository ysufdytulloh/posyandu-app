<?php

namespace App\Filament\Resources\JenisPmtResource\Pages;

use App\Filament\Resources\JenisPmtResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJenisPmts extends ListRecords
{
    protected static string $resource = JenisPmtResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
