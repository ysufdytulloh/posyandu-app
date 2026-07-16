<?php

namespace App\Filament\Resources\PosyanduResource\Pages;

use App\Filament\Resources\PosyanduResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPosyandu extends EditRecord
{
    protected static string $resource = PosyanduResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
