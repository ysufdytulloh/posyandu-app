<?php

namespace App\Filament\Widgets;

use App\Models\TimbangBalita;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class TimbangChart extends ChartWidget
{
    protected static ?string $heading = 'Timbang Balita per Bulan';
    protected static ?int    $sort    = 2;

    protected function getData(): array
    {
        $data = collect(range(5, 0))->map(function ($monthsAgo) {
            $date  = Carbon::now()->subMonths($monthsAgo);
            $count = TimbangBalita::whereYear('tgl_periksa', $date->year)
                ->whereMonth('tgl_periksa', $date->month)
                ->count();

            return [
                'month' => $date->translatedFormat('M Y'),
                'count' => $count,
            ];
        });

        return [
            'datasets' => [
                [
                    'label'           => 'Jumlah Timbang',
                    'data'            => $data->pluck('count')->toArray(),
                    // Ubah format warna ke RGBA agar transparan untuk isi area di bawah garis
                    'backgroundColor' => 'rgba(5, 150, 105, 0.2)',
                    'borderColor'     => '#059669',
                    'borderWidth'     => 2,
                    'tension'         => 0.3, // <-- Tambahan: Membuat garisnya melengkung/smooth, tidak kaku
                    'fill'            => true, // Memberikan efek area chart
                ],
            ],
            'labels' => $data->pluck('month')->toArray(),
        ];
    }

    protected function getType(): string
    {
        // Ubah dari 'bar' menjadi 'line'
        return 'line';
    }
}
