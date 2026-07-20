<?php
namespace App\Filament\Kader\Resources\AnakResource\Pages;
use App\Filament\Kader\Resources\AnakResource;
use Filament\Resources\Pages\ListRecords;
class ListAnaks extends ListRecords {
    protected function getHeaderActions(): array { return [\Filament\Actions\CreateAction::make()]; } protected static string $resource = AnakResource::class; }
