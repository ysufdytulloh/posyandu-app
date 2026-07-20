<?php
namespace App\Filament\Kader\Resources\ObatCacingResource\Pages;
use App\Filament\Kader\Resources\ObatCacingResource;
use Filament\Resources\Pages\ListRecords;
class ListObatCacings extends ListRecords {
    protected function getHeaderActions(): array { return [\Filament\Actions\CreateAction::make()]; } protected static string $resource = ObatCacingResource::class; }
