<?php
namespace App\Filament\Kader\Resources\SdidtkResource\Pages;
use App\Filament\Kader\Resources\SdidtkResource;
use Filament\Resources\Pages\CreateRecord;
class CreateSdidtk extends CreateRecord { protected static string $resource = SdidtkResource::class; protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); }
    protected function getFormActions(): array { return [$this->getCreateFormAction()->label('Buat Data'), $this->getCancelFormAction()->label('Batal')]; }
}
