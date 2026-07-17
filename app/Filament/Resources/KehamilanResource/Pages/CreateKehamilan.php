<?php

namespace App\Filament\Resources\KehamilanResource\Pages;

use App\Filament\Resources\KehamilanResource;
use Filament\Resources\Pages\CreateRecord;

class CreateKehamilan extends CreateRecord
{
    protected static string $resource = KehamilanResource::class;

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
