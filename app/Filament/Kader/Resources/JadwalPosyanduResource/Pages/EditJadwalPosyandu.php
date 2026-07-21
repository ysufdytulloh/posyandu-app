<?php

namespace App\Filament\Kader\Resources\JadwalPosyanduResource\Pages;

use App\Filament\Kader\Resources\JadwalPosyanduResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJadwalPosyandu extends EditRecord
{
    protected static string $resource = JadwalPosyanduResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
