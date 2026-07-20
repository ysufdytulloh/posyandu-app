<?php
namespace App\Filament\Kader\Resources\AnakResource\Pages;
use App\Filament\Kader\Resources\AnakResource;
use Filament\Resources\Pages\EditRecord;
class EditAnak extends EditRecord { protected static string $resource = AnakResource::class; protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); }
    protected function getFormActions(): array { return [$this->getSaveFormAction()->label('Simpan Data'), $this->getCancelFormAction()->label('Batal')]; }
}
