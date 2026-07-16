<?php

namespace App\Filament\Resources\PeriksaLansiaResource\Pages;

use App\Filament\Resources\PeriksaLansiaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePeriksaLansia extends CreateRecord
{
    protected static string $resource = PeriksaLansiaResource::class;

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
