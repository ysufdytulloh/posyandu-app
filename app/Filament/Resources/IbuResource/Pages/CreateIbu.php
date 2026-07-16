<?php

namespace App\Filament\Resources\IbuResource\Pages;

use App\Filament\Resources\IbuResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateIbu extends CreateRecord
{
    protected static string $resource = IbuResource::class;

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
