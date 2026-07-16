<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\TimbangChart;
use App\Filament\Widgets\StatusGiziChart;

class Dashboard extends \Filament\Pages\Dashboard
{
    protected static ?string $navigationLabel = 'Beranda';
    protected static ?string $title           = 'Beranda';
    protected static ?string $navigationIcon  = 'heroicon-o-home';
    protected static ?int    $navigationSort  = -1;

    public function getWidgets(): array
    {
        return [
            StatsOverview::class,
            TimbangChart::class,
            StatusGiziChart::class,
        ];
    }

    public function getColumns(): int | array
    {
        return [
            'md' => 2,
            'xl' => 2,
        ];
    }
}
