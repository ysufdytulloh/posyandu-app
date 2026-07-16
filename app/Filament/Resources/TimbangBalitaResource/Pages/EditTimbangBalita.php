<?php

namespace App\Filament\Resources\TimbangBalitaResource\Pages;

use App\Filament\Resources\TimbangBalitaResource;
use Filament\Resources\Pages\EditRecord;

class EditTimbangBalita extends EditRecord
{
    protected static string $resource = TimbangBalitaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()
                ->label('Simpan Data'),
            $this->getCancelFormAction()
                ->label('Batal'),
        ];
    }
}
