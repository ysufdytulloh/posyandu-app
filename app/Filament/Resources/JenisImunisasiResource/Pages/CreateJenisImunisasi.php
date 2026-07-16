<?php

namespace App\Filament\Resources\JenisImunisasiResource\Pages;

use App\Filament\Resources\JenisImunisasiResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateJenisImunisasi extends CreateRecord
{
    protected static string $resource = JenisImunisasiResource::class;

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
