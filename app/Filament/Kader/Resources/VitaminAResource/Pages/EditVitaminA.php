<?php
namespace App\Filament\Kader\Resources\VitaminAResource\Pages;
use App\Filament\Kader\Resources\VitaminAResource;
use Filament\Resources\Pages\EditRecord;
class EditVitaminA extends EditRecord { protected static string $resource = VitaminAResource::class; protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); }
    protected function getFormActions(): array { return [$this->getSaveFormAction()->label('Simpan Data'), $this->getCancelFormAction()->label('Batal')]; }
}
