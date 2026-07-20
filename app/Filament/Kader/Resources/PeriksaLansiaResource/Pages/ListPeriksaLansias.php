<?php
namespace App\Filament\Kader\Resources\PeriksaLansiaResource\Pages;
use App\Filament\Kader\Resources\PeriksaLansiaResource;
use Filament\Resources\Pages\ListRecords;
class ListPeriksaLansias extends ListRecords {
    protected function getHeaderActions(): array { return [\Filament\Actions\CreateAction::make()]; } protected static string $resource = PeriksaLansiaResource::class; }
