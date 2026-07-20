<?php
namespace App\Filament\Kader\Resources\KehamilanResource\Pages;
use App\Filament\Kader\Resources\KehamilanResource;
use Filament\Resources\Pages\ListRecords;
class ListKehamilans extends ListRecords {
    protected function getHeaderActions(): array { return [\Filament\Actions\CreateAction::make()]; } protected static string $resource = KehamilanResource::class; }
