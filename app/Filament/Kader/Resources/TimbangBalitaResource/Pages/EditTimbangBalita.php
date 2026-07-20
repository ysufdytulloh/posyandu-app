<?php
namespace App\Filament\Kader\Resources\TimbangBalitaResource\Pages;
use App\Filament\Kader\Resources\TimbangBalitaResource;
use Filament\Resources\Pages\EditRecord;
class EditTimbangBalita extends EditRecord {
    protected static string $resource = TimbangBalitaResource::class;
    protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); }
}
