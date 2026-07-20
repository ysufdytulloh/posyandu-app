<?php

namespace App\Filament\Kader\Widgets;

use App\Models\Anak;
use App\Models\HasilGizi;
use App\Models\Kehamilan;
use App\Models\Lansia;
use App\Models\TimbangBalita;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class KaderStatsOverview extends Widget
{
    protected static ?int $sort = 1;
    protected static string $view = 'filament.kader.widgets.kader-stats-overview';
    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $posyanduId = Auth::user()->posyandu_id;

        $stuntingBaru = HasilGizi::whereIn('status_tbU', ['Pendek', 'Sangat Pendek'])
            ->whereHas('timbang', fn ($q) => $q
                ->where('posyandu_id', $posyanduId)
                ->whereMonth('tgl_periksa', now()->month)
                ->whereYear('tgl_periksa', now()->year)
            )->count();

        $giziKurangBaru = HasilGizi::whereIn('status_bbU', ['Gizi Kurang', 'Gizi Buruk'])
            ->whereHas('timbang', fn ($q) => $q
                ->where('posyandu_id', $posyanduId)
                ->whereMonth('tgl_periksa', now()->month)
                ->whereYear('tgl_periksa', now()->year)
            )->count();

        return [
            'posyandu'   => \App\Models\Posyandu::find($posyanduId),
            'kader'      => Auth::user(),
            'stats' => [
                [
                    'label'       => 'Total Balita',
                    'value'       => Anak::where('posyandu_id', $posyanduId)->count(),
                    'description' => 'Balita terdaftar',
                    'icon'        => 'heroicon-o-face-smile',
                    'color'       => '#f59e0b',
                    'bg'          => '#fef3c7',
                    'alert'       => false,
                    'baru'        => 0,
                ],
                [
                    'label'       => 'Total Lansia',
                    'value'       => Lansia::where('posyandu_id', $posyanduId)->count(),
                    'description' => 'Lansia terdaftar',
                    'icon'        => 'heroicon-o-user-circle',
                    'color'       => '#8b5cf6',
                    'bg'          => '#ede9fe',
                    'alert'       => false,
                    'baru'        => 0,
                ],
                [
                    'label'       => 'Timbang Bulan Ini',
                    'value'       => TimbangBalita::where('posyandu_id', $posyanduId)
                        ->whereMonth('tgl_periksa', now()->month)
                        ->whereYear('tgl_periksa', now()->year)
                        ->count(),
                    'description' => now()->translatedFormat('F Y'),
                    'icon'        => 'heroicon-o-scale',
                    'color'       => '#0ea5e9',
                    'bg'          => '#e0f2fe',
                    'alert'       => false,
                    'baru'        => 0,
                ],
                [
                    'label'       => 'Ibu Hamil Aktif',
                    'value'       => Kehamilan::where('status', 'aktif')
                        ->whereHas('ibu', fn($q) => $q->where('posyandu_id', $posyanduId))
                        ->count(),
                    'description' => 'Sedang hamil',
                    'icon'        => 'heroicon-o-heart',
                    'color'       => '#ec4899',
                    'bg'          => '#fce7f3',
                    'alert'       => false,
                    'baru'        => 0,
                ],
                [
                    'label'       => 'Stunting',
                    'value'       => HasilGizi::whereIn('status_tbU', ['Pendek', 'Sangat Pendek'])
                        ->whereHas('timbang', fn($q) => $q->where('posyandu_id', $posyanduId))
                        ->count(),
                    'description' => 'Balita pendek/sangat pendek',
                    'icon'        => 'heroicon-o-arrow-trending-down',
                    'color'       => '#ef4444',
                    'bg'          => '#fee2e2',
                    'alert'       => $stuntingBaru > 0,
                    'baru'        => $stuntingBaru,
                ],
                [
                    'label'       => 'Gizi Kurang',
                    'value'       => HasilGizi::whereIn('status_bbU', ['Gizi Kurang', 'Gizi Buruk'])
                        ->whereHas('timbang', fn($q) => $q->where('posyandu_id', $posyanduId))
                        ->count(),
                    'description' => 'Balita gizi kurang/buruk',
                    'icon'        => 'heroicon-o-exclamation-triangle',
                    'color'       => '#f97316',
                    'bg'          => '#ffedd5',
                    'alert'       => $giziKurangBaru > 0,
                    'baru'        => $giziKurangBaru,
                ],
            ],
        ];
    }
}
