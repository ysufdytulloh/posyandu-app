<?php

namespace App\Filament\Resources\PeriksaLansiaResource\Pages;

use App\Filament\Resources\PeriksaLansiaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPeriksaLansia extends EditRecord
{
    protected static string $resource = PeriksaLansiaResource::class;

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
