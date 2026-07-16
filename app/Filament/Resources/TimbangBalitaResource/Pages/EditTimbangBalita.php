<?php

namespace App\Filament\Resources\TimbangBalitaResource\Pages;

use App\Filament\Resources\TimbangBalitaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTimbangBalita extends EditRecord
{
    protected static string $resource = TimbangBalitaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
