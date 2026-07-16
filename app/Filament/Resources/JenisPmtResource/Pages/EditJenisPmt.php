<?php

namespace App\Filament\Resources\JenisPmtResource\Pages;

use App\Filament\Resources\JenisPmtResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJenisPmt extends EditRecord
{
    protected static string $resource = JenisPmtResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
