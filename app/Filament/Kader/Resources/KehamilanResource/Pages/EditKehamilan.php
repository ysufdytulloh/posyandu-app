<?php
namespace App\Filament\Kader\Resources\KehamilanResource\Pages;
use App\Filament\Kader\Resources\KehamilanResource;
use Filament\Resources\Pages\EditRecord;
class EditKehamilan extends EditRecord { protected static string $resource = KehamilanResource::class; protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); }
    protected function getFormActions(): array { return [$this->getSaveFormAction()->label('Simpan Data'), $this->getCancelFormAction()->label('Batal')]; }
}
