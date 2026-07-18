<?php
namespace App\Filament\Resources\SdidtkResource\Pages;
use App\Filament\Resources\SdidtkResource;
use Filament\Resources\Pages\EditRecord;
class EditSdidtk extends EditRecord {
    protected static string $resource = SdidtkResource::class;
    protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); }
    protected function getFormActions(): array {
        return [$this->getSaveFormAction()->label('Simpan Data SDIDTK'), $this->getCancelFormAction()->label('Batal')];
    }
}
