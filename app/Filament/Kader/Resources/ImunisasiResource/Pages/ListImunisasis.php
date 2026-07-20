<?php
namespace App\Filament\Kader\Resources\ImunisasiResource\Pages;
use App\Filament\Kader\Resources\ImunisasiResource;
use Filament\Resources\Pages\ListRecords;
class ListImunisasis extends ListRecords {
    protected function getHeaderActions(): array { return [\Filament\Actions\CreateAction::make()]; } protected static string $resource = ImunisasiResource::class; }
