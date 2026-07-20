<?php

namespace App\Filament\Kader\Widgets;

use App\Models\TimbangBalita;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class KaderTimbangChart extends ChartWidget
{
    protected static ?string $heading     = 'Timbang Balita per Bulan';
    protected static ?int    $sort        = 2;
    protected int | string | array $columnSpan = 1;

    protected function getData(): array
    {
        $posyanduId = Auth::user()->posyandu_id;

        $data = collect(range(5, 0))->map(function ($monthsAgo) use ($posyanduId) {
            $date  = Carbon::now()->subMonths($monthsAgo);
            $count = TimbangBalita::where('posyandu_id', $posyanduId)
                ->whereYear('tgl_periksa', $date->year)
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
                    'backgroundColor' => 'rgba(37, 99, 235, 0.2)', // Warna latar transparan khas area chart
                    'borderColor'     => '#2563eb',
                    'borderWidth'     => 2,
                    'tension'         => 0.3, // Membuat garis melengkung/smooth
                    'fill'            => true, // Mengaktifkan efek arsiran di bawah garis
                ],
            ],
            'labels' => $data->pluck('month')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
