<?php

namespace App\Filament\Resources\JenisPmtResource\Pages;

use App\Filament\Resources\JenisPmtResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJenisPmt extends EditRecord
{
    protected static string $resource = JenisPmtResource::class;

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
