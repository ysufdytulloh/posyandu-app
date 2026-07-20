<?php
namespace App\Filament\Kader\Resources\PeriksaKehamilanResource\Pages;
use App\Filament\Kader\Resources\PeriksaKehamilanResource;
use Filament\Resources\Pages\CreateRecord;
class CreatePeriksaKehamilan extends CreateRecord { protected static string $resource = PeriksaKehamilanResource::class; protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); }
    protected function getFormActions(): array { return [$this->getCreateFormAction()->label('Buat Data'), $this->getCancelFormAction()->label('Batal')]; }
}
