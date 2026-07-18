<?php

namespace App\Filament\Resources\PeriksaKehamilanResource\Pages;

use App\Filament\Resources\PeriksaKehamilanResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePeriksaKehamilan extends CreateRecord
{
    protected static string $resource = PeriksaKehamilanResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()->label('Buat Data Pemeriksaan'),
            $this->getCancelFormAction()->label('Batal'),
        ];
    }
}
