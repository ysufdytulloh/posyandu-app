<?php

namespace App\Filament\Resources\JenisPmtResource\Pages;

use App\Filament\Resources\JenisPmtResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateJenisPmt extends CreateRecord
{
    protected static string $resource = JenisPmtResource::class;

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
