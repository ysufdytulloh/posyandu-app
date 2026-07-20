<?php
namespace App\Filament\Kader\Resources\PeriksaKehamilanResource\Pages;
use App\Filament\Kader\Resources\PeriksaKehamilanResource;
use Filament\Resources\Pages\EditRecord;
class EditPeriksaKehamilan extends EditRecord { protected static string $resource = PeriksaKehamilanResource::class; protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); }
    protected function getFormActions(): array { return [$this->getSaveFormAction()->label('Simpan Data'), $this->getCancelFormAction()->label('Batal')]; }
}
