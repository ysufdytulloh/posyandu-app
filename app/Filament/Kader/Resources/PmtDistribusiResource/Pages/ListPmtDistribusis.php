<?php
namespace App\Filament\Kader\Resources\PmtDistribusiResource\Pages;
use App\Filament\Kader\Resources\PmtDistribusiResource;
use Filament\Resources\Pages\ListRecords;
class ListPmtDistribusis extends ListRecords {
    protected function getHeaderActions(): array { return [\Filament\Actions\CreateAction::make()]; } protected static string $resource = PmtDistribusiResource::class; }
