<?php

namespace App\Filament\Kader\Resources\JadwalPosyanduResource\Pages;

use App\Filament\Kader\Resources\JadwalPosyanduResource;
use Filament\Resources\Pages\CreateRecord;

class CreateJadwalPosyandu extends CreateRecord
{
    protected static string $resource = JadwalPosyanduResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()->label('Buat Data'),
            $this->getCancelFormAction()->label('Batal'),
        ];
    }
}
