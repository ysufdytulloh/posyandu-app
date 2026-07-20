<?php
namespace App\Filament\Kader\Resources\PeriksaKehamilanResource\Pages;
use App\Filament\Kader\Resources\PeriksaKehamilanResource;
use Filament\Resources\Pages\ListRecords;
class ListPeriksaKehamilans extends ListRecords {
    protected function getHeaderActions(): array { return [\Filament\Actions\CreateAction::make()]; } protected static string $resource = PeriksaKehamilanResource::class; }
