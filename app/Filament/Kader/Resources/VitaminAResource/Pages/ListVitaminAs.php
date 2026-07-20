<?php
namespace App\Filament\Kader\Resources\VitaminAResource\Pages;
use App\Filament\Kader\Resources\VitaminAResource;
use Filament\Resources\Pages\ListRecords;
class ListVitaminAs extends ListRecords {
    protected function getHeaderActions(): array { return [\Filament\Actions\CreateAction::make()]; } protected static string $resource = VitaminAResource::class; }
