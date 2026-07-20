<?php
namespace App\Filament\Kader\Resources\IbuResource\Pages;
use App\Filament\Kader\Resources\IbuResource;
use Filament\Resources\Pages\ListRecords;
class ListIbus extends ListRecords {
    protected function getHeaderActions(): array { return [\Filament\Actions\CreateAction::make()]; } protected static string $resource = IbuResource::class; }
