<?php

namespace App\Filament\Resources\PeriksaLansiaResource\Pages;

use App\Filament\Resources\PeriksaLansiaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPeriksaLansia extends EditRecord
{
    protected static string $resource = PeriksaLansiaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
