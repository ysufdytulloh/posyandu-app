<?php

namespace App\Filament\Resources\ImunisasiResource\Pages;

use App\Filament\Resources\ImunisasiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditImunisasi extends EditRecord
{
    protected static string $resource = ImunisasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
