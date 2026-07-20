<?php
namespace App\Filament\Kader\Resources\LansiaResource\Pages;
use App\Filament\Kader\Resources\LansiaResource;
use Filament\Resources\Pages\ListRecords;
class ListLansias extends ListRecords {
    protected function getHeaderActions(): array { return [\Filament\Actions\CreateAction::make()]; } protected static string $resource = LansiaResource::class; }
