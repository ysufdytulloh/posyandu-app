<?php

namespace App\Filament\Resources\PmtDistribusiResource\Pages;

use App\Filament\Resources\PmtDistribusiResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePmtDistribusi extends CreateRecord
{
    protected static string $resource = PmtDistribusiResource::class;

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
