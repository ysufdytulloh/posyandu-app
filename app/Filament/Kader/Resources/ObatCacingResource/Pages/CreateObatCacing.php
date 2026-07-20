<?php
namespace App\Filament\Kader\Resources\ObatCacingResource\Pages;
use App\Filament\Kader\Resources\ObatCacingResource;
use Filament\Resources\Pages\CreateRecord;
class CreateObatCacing extends CreateRecord { protected static string $resource = ObatCacingResource::class; protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); }
    protected function getFormActions(): array { return [$this->getCreateFormAction()->label('Buat Data'), $this->getCancelFormAction()->label('Batal')]; }
}
