<?php

namespace App\Filament\Kader\Widgets;

use App\Models\HasilGizi;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class KaderStatusGiziChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Status Gizi Balita';
    protected static ?int    $sort    = 3;

    protected function getData(): array
    {
        $posyanduId = Auth::user()->posyandu_id;

        $giziBuruk  = HasilGizi::where('status_bbU', 'Gizi Buruk')
            ->whereHas('timbang', fn($q) => $q->where('posyandu_id', $posyanduId))->count();
        $giziKurang = HasilGizi::where('status_bbU', 'Gizi Kurang')
            ->whereHas('timbang', fn($q) => $q->where('posyandu_id', $posyanduId))->count();
        $normal     = HasilGizi::where('status_bbU', 'Normal')
            ->whereHas('timbang', fn($q) => $q->where('posyandu_id', $posyanduId))->count();
        $giziLebih  = HasilGizi::where('status_bbU', 'Gizi Lebih')
            ->whereHas('timbang', fn($q) => $q->where('posyandu_id', $posyanduId))->count();

        return [
            'datasets' => [
                [
                    'label'                => 'Jumlah Balita',
                    'data'                 => [$giziBuruk, $giziKurang, $normal, $giziLebih],
                    'borderColor'          => '#2563eb',
                    'backgroundColor'      => 'rgba(37, 99, 235, 0.2)',
                    'borderWidth'          => 2,
                    'tension'              => 0.3,
                    'fill'                 => true,
                    'pointBackgroundColor' => ['#EF4444', '#F59E0B', '#059669', '#3B82F6'],
                    'pointBorderColor'     => '#ffffff',
                    'pointBorderWidth'     => 1,
                    'pointRadius'          => 5,
                    'pointHoverRadius'     => 8,
                ],
            ],
            'labels' => ['Gizi Buruk', 'Gizi Kurang', 'Normal', 'Gizi Lebih'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
