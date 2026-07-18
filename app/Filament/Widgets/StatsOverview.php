<?php

namespace App\Filament\Widgets;

use App\Models\Anak;
use App\Models\HasilGizi;
use App\Models\Lansia;
use App\Models\Posyandu;
use App\Models\User;
use Filament\Widgets\Widget;
use Filament\Forms\Components\Select;

class StatsOverview extends Widget
{
    protected static ?int $sort = 1;
    protected static string $view = 'filament.widgets.stats-overview';
    protected int | string | array $columnSpan = 'full';

    public ?string $posyandu_id = null;

    protected function getViewData(): array
    {
        $posyanduId = $this->posyandu_id;

        $stuntingTotal = HasilGizi::whereIn('status_tbU', ['Pendek', 'Sangat Pendek'])
            ->when($posyanduId, fn($q) => $q->whereHas('timbang', fn($q) =>
                $q->where('posyandu_id', $posyanduId)))
            ->count();

        $giziKurangTotal = HasilGizi::whereIn('status_bbU', ['Gizi Kurang', 'Gizi Buruk'])
            ->when($posyanduId, fn($q) => $q->whereHas('timbang', fn($q) =>
                $q->where('posyandu_id', $posyanduId)))
            ->count();

        $stuntingBaru = HasilGizi::whereIn('status_tbU', ['Pendek', 'Sangat Pendek'])
            ->whereHas('timbang', fn ($q) => $q
                ->whereMonth('tgl_periksa', now()->month)
                ->whereYear('tgl_periksa', now()->year)
                ->when($posyanduId, fn($q) => $q->where('posyandu_id', $posyanduId))
            )->count();

        $giziKurangBaru = HasilGizi::whereIn('status_bbU', ['Gizi Kurang', 'Gizi Buruk'])
            ->whereHas('timbang', fn ($q) => $q
                ->whereMonth('tgl_periksa', now()->month)
                ->whereYear('tgl_periksa', now()->year)
                ->when($posyanduId, fn($q) => $q->where('posyandu_id', $posyanduId))
            )->count();

        return [
            'stats' => [
                [
                    'label'       => 'Total Posyandu',
                    'value'       => Posyandu::where('is_active', true)->count(),
                    'description' => 'Posyandu aktif',
                    'icon'        => 'heroicon-o-building-office',
                    'color'       => '#0ea5e9',
                    'bg'          => '#e0f2fe',
                    'alert'       => false,
                    'baru'        => 0,
                ],
                [
                    'label'       => 'Total Balita',
                    'value'       => Anak::when($posyanduId, fn($q) => $q->where('posyandu_id', $posyanduId))->count(),
                    'description' => $posyanduId ? 'Di posyandu ini' : 'Balita terdaftar',
                    'icon'        => 'heroicon-o-face-smile',
                    'color'       => '#f59e0b',
                    'bg'          => '#fef3c7',
                    'alert'       => false,
                    'baru'        => 0,
                ],
                [
                    'label'       => 'Total Lansia',
                    'value'       => Lansia::when($posyanduId, fn($q) => $q->where('posyandu_id', $posyanduId))->count(),
                    'description' => $posyanduId ? 'Di posyandu ini' : 'Lansia terdaftar',
                    'icon'        => 'heroicon-o-user-circle',
                    'color'       => '#8b5cf6',
                    'bg'          => '#ede9fe',
                    'alert'       => false,
                    'baru'        => 0,
                ],
                [
                    'label'       => 'Total Kader',
                    'value'       => User::where('role', 'kader')
                        ->when($posyanduId, fn($q) => $q->where('posyandu_id', $posyanduId))
                        ->count(),
                    'description' => $posyanduId ? 'Di posyandu ini' : 'Kader aktif',
                    'icon'        => 'heroicon-o-users',
                    'color'       => '#059669',
                    'bg'          => '#d1fae5',
                    'alert'       => false,
                    'baru'        => 0,
                ],
                [
                    'label'       => 'Stunting',
                    'value'       => $stuntingTotal,
                    'description' => 'Balita pendek/sangat pendek',
                    'icon'        => 'heroicon-o-arrow-trending-down',
                    'color'       => '#ef4444',
                    'bg'          => '#fee2e2',
                    'alert'       => $stuntingBaru > 0,
                    'baru'        => $stuntingBaru,
                ],
                [
                    'label'       => 'Gizi Kurang',
                    'value'       => $giziKurangTotal,
                    'description' => 'Balita gizi kurang/buruk',
                    'icon'        => 'heroicon-o-exclamation-triangle',
                    'color'       => '#f97316',
                    'bg'          => '#ffedd5',
                    'alert'       => $giziKurangBaru > 0,
                    'baru'        => $giziKurangBaru,
                ],
            ],
            'posyandus' => Posyandu::where('is_active', true)->pluck('nama', 'id'),
        ];
    }
}
