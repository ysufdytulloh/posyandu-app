<?php
namespace App\Filament\Resources\ObatCacingResource\Pages;
use App\Filament\Resources\ObatCacingResource;
use Filament\Resources\Pages\CreateRecord;
class CreateObatCacing extends CreateRecord {
    protected static string $resource = ObatCacingResource::class;
    protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); }
    protected function getFormActions(): array {
        return [$this->getCreateFormAction()->label('Buat Data Obat Cacing'), $this->getCancelFormAction()->label('Batal')];
    }
}
