<?php
namespace App\Filament\Kader\Resources\IbuResource\Pages;
use App\Filament\Kader\Resources\IbuResource;
use Filament\Resources\Pages\EditRecord;
class EditIbu extends EditRecord { protected static string $resource = IbuResource::class; protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); }
    protected function getFormActions(): array { return [$this->getSaveFormAction()->label('Simpan Data'), $this->getCancelFormAction()->label('Batal')]; }
}
