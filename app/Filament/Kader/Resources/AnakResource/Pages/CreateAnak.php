<?php
namespace App\Filament\Kader\Resources\AnakResource\Pages;
use App\Filament\Kader\Resources\AnakResource;
use Filament\Resources\Pages\CreateRecord;
class CreateAnak extends CreateRecord { protected static string $resource = AnakResource::class; protected function mutateFormDataBeforeCreate(array $data): array { $data["posyandu_id"] = auth()->user()->posyandu_id; return $data; }
    protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); }
    protected function getFormActions(): array { return [$this->getCreateFormAction()->label('Buat Data'), $this->getCancelFormAction()->label('Batal')]; }
}
