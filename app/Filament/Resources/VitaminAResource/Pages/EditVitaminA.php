<?php

namespace App\Filament\Resources\VitaminAResource\Pages;

use App\Filament\Resources\VitaminAResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVitaminA extends EditRecord
{
    protected static string $resource = VitaminAResource::class;

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
