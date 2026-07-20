<?php
namespace App\Filament\Kader\Resources\PmtDistribusiResource\Pages;
use App\Filament\Kader\Resources\PmtDistribusiResource;
use Filament\Resources\Pages\EditRecord;
class EditPmtDistribusi extends EditRecord { protected static string $resource = PmtDistribusiResource::class; protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); }
    protected function getFormActions(): array { return [$this->getSaveFormAction()->label('Simpan Data'), $this->getCancelFormAction()->label('Batal')]; }
}
