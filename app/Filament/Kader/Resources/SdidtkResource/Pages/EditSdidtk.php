<?php
namespace App\Filament\Kader\Resources\SdidtkResource\Pages;
use App\Filament\Kader\Resources\SdidtkResource;
use Filament\Resources\Pages\EditRecord;
class EditSdidtk extends EditRecord { protected static string $resource = SdidtkResource::class; protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); }
    protected function getFormActions(): array { return [$this->getSaveFormAction()->label('Simpan Data'), $this->getCancelFormAction()->label('Batal')]; }
}
