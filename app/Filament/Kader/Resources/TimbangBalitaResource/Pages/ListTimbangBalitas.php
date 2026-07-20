<?php
namespace App\Filament\Kader\Resources\TimbangBalitaResource\Pages;
use App\Filament\Kader\Resources\TimbangBalitaResource;
use Filament\Resources\Pages\ListRecords;
class ListTimbangBalitas extends ListRecords {
    protected function getHeaderActions(): array { return [\Filament\Actions\CreateAction::make()]; }
    protected static string $resource = TimbangBalitaResource::class;
}
