<?php

namespace App\Filament\Widgets;

use App\Models\Anak;
use App\Models\HasilGizi;
use App\Models\Lansia;
use App\Models\Posyandu;
use App\Models\User;
use Filament\Widgets\Widget;

class StatsOverview extends Widget
{
    protected static ?int $sort = 1;
    protected static string $view = 'filament.widgets.stats-overview';
    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        return [
            'stats' => [
                [
                    'label'       => 'Total Posyandu',
                    'value'       => Posyandu::where('is_active', true)->count(),
                    'description' => 'Posyandu aktif',
                    'icon'        => 'heroicon-o-building-office',
                    'color'       => '#0ea5e9',
                    'bg'          => '#e0f2fe',
                ],
                [
                    'label'       => 'Total Balita',
                    'value'       => Anak::count(),
                    'description' => 'Balita terdaftar',
                    'icon'        => 'heroicon-o-face-smile',
                    'color'       => '#f59e0b',
                    'bg'          => '#fef3c7',
                ],
                [
                    'label'       => 'Total Lansia',
                    'value'       => Lansia::count(),
                    'description' => 'Lansia terdaftar',
                    'icon'        => 'heroicon-o-user-circle',
                    'color'       => '#8b5cf6',
                    'bg'          => '#ede9fe',
                ],
                [
                    'label'       => 'Total Kader',
                    'value'       => User::where('role', 'kader')->count(),
                    'description' => 'Kader aktif',
                    'icon'        => 'heroicon-o-users',
                    'color'       => '#059669',
                    'bg'          => '#d1fae5',
                ],
                [
                    'label'       => 'Stunting',
                    'value'       => HasilGizi::whereIn('status_tbU', ['Pendek', 'Sangat Pendek'])->count(),
                    'description' => 'Balita pendek/sangat pendek',
                    'icon'        => 'heroicon-o-arrow-trending-down',
                    'color'       => '#ef4444',
                    'bg'          => '#fee2e2',
                ],
                [
                    'label'       => 'Gizi Kurang',
                    'value'       => HasilGizi::whereIn('status_bbU', ['Gizi Kurang', 'Gizi Buruk'])->count(),
                    'description' => 'Balita gizi kurang/buruk',
                    'icon'        => 'heroicon-o-exclamation-triangle',
                    'color'       => '#f97316',
                    'bg'          => '#ffedd5',
                ],
            ],
        ];
    }
}
