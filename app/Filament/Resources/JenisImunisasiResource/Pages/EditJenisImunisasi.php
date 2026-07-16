<?php

namespace App\Filament\Resources\JenisImunisasiResource\Pages;

use App\Filament\Resources\JenisImunisasiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJenisImunisasi extends EditRecord
{
    protected static string $resource = JenisImunisasiResource::class;

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
