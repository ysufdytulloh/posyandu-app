<?php
namespace App\Filament\Kader\Resources\ObatCacingResource\Pages;
use App\Filament\Kader\Resources\ObatCacingResource;
use Filament\Resources\Pages\EditRecord;
class EditObatCacing extends EditRecord { protected static string $resource = ObatCacingResource::class; protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); }
    protected function getFormActions(): array { return [$this->getSaveFormAction()->label('Simpan Data'), $this->getCancelFormAction()->label('Batal')]; }
}
