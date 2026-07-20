<?php
namespace App\Filament\Kader\Resources\VitaminAResource\Pages;
use App\Filament\Kader\Resources\VitaminAResource;
use Filament\Resources\Pages\CreateRecord;
class CreateVitaminA extends CreateRecord { protected static string $resource = VitaminAResource::class; protected function mutateFormDataBeforeCreate(array $data): array { $data["posyandu_id"] = auth()->user()->posyandu_id; return $data; }
    protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); }
    protected function getFormActions(): array { return [$this->getCreateFormAction()->label('Buat Data'), $this->getCancelFormAction()->label('Batal')]; }
}
