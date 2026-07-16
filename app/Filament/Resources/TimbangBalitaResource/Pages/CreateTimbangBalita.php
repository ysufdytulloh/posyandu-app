<?php

namespace App\Filament\Resources\TimbangBalitaResource\Pages;

use App\Filament\Resources\TimbangBalitaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTimbangBalita extends CreateRecord
{
    protected static string $resource = TimbangBalitaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()
                ->label('Buat Data'),
            $this->getCancelFormAction()
                ->label('Batal'),
        ];
    }
}
