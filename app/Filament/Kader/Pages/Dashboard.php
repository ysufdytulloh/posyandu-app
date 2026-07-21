<?php

namespace App\Filament\Kader\Pages;

use App\Filament\Kader\Widgets\KaderStatsOverview;
use App\Filament\Kader\Widgets\KaderTimbangChart;
use App\Filament\Kader\Widgets\KaderStatusGiziChart;
use Filament\Pages\Page;

class Dashboard extends \Filament\Pages\Dashboard
{
    protected static ?string $navigationLabel = 'Beranda';
    protected static ?string $title           = 'Beranda';
    protected static ?string $navigationIcon  = 'heroicon-o-home';
    protected static ?int    $navigationSort  = -1;

    public function getWidgets(): array
    {
        return [
            KaderStatsOverview::class,
            KaderTimbangChart::class,
            KaderStatusGiziChart::class,
        ];
    }

    public function getColumns(): int | array
    {
        return ['md' => 2, 'xl' => 2];
    }


}
