<?php
namespace App\Filament\Kader\Resources\ImunisasiResource\Pages;
use App\Filament\Kader\Resources\ImunisasiResource;
use Filament\Resources\Pages\EditRecord;
class EditImunisasi extends EditRecord { protected static string $resource = ImunisasiResource::class; protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); }
    protected function getFormActions(): array { return [$this->getSaveFormAction()->label('Simpan Data'), $this->getCancelFormAction()->label('Batal')]; }
}
