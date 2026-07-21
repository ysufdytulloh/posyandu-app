<?php

namespace App\Filament\Resources\JadwalPosyanduResource\Pages;

use App\Filament\Resources\JadwalPosyanduResource;
use Filament\Resources\Pages\EditRecord;

class EditJadwalPosyandu extends EditRecord
{
    protected static string $resource = JadwalPosyanduResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()->label('Simpan Data'),
            $this->getCancelFormAction()->label('Batal'),
        ];
    }
}
