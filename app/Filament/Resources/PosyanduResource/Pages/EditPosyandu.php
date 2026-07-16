<?php

namespace App\Filament\Resources\PosyanduResource\Pages;

use App\Filament\Resources\PosyanduResource;
use Filament\Resources\Pages\EditRecord;

class EditPosyandu extends EditRecord
{
    protected static string $resource = PosyanduResource::class;

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
