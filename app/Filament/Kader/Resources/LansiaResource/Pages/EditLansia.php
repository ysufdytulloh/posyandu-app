<?php
namespace App\Filament\Kader\Resources\LansiaResource\Pages;
use App\Filament\Kader\Resources\LansiaResource;
use Filament\Resources\Pages\EditRecord;
class EditLansia extends EditRecord { protected static string $resource = LansiaResource::class; protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); }
    protected function getFormActions(): array { return [$this->getSaveFormAction()->label('Simpan Data'), $this->getCancelFormAction()->label('Batal')]; }
}
