<?php
namespace App\Filament\Kader\Resources\SdidtkResource\Pages;
use App\Filament\Kader\Resources\SdidtkResource;
use Filament\Resources\Pages\ListRecords;
class ListSdidtks extends ListRecords {
    protected function getHeaderActions(): array { return [\Filament\Actions\CreateAction::make()]; } protected static string $resource = SdidtkResource::class; }
