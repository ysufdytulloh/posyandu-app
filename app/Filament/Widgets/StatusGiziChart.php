<?php

namespace App\Filament\Widgets;

use App\Models\HasilGizi;
use Filament\Widgets\ChartWidget;

class StatusGiziChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Status Gizi Balita'; // Ubah judulnya agar lebih pas
    protected static ?int    $sort    = 3;

    protected function getData(): array
    {
        // Tetap ambil datanya
        $giziBuruk  = HasilGizi::where('status_bbU', 'Gizi Buruk')->count();
        $giziKurang = HasilGizi::where('status_bbU', 'Gizi Kurang')->count();
        $normal     = HasilGizi::where('status_bbU', 'Normal')->count();
        $giziLebih  = HasilGizi::where('status_bbU', 'Gizi Lebih')->count();

        return [
            'datasets' => [
                [
                    'label'                => 'Jumlah Balita',
                    // URUTAN DATA DIUBAH: Dari yang paling kurang ke berlebih
                    'data'                 => [$giziBuruk, $giziKurang, $normal, $giziLebih],
                    'borderColor'          => '#3B82F6', // Garis utama warna biru
                    'backgroundColor'      => 'rgba(59, 130, 246, 0.2)', // Area bawah transparan
                    'borderWidth'          => 2,
                    'tension'              => 0.3, // Membuatnya melengkung halus seperti kurva
                    'fill'                 => true,
                    // Titik-titiknya diberi warna berbeda sebagai penanda tingkat bahaya/aman
                    'pointBackgroundColor' => ['#EF4444', '#F59E0B', '#059669', '#3B82F6'],
                    'pointBorderColor'     => '#ffffff',
                    'pointBorderWidth'     => 1,
                    'pointRadius'          => 5,
                    'pointHoverRadius'     => 8,
                ],
            ],
            // URUTAN LABEL DIUBAH MENGIKUTI DATA
            'labels' => ['Gizi Buruk', 'Gizi Kurang', 'Normal', 'Gizi Lebih'],
        ];
    }

    protected function getType(): string
    {
        return 'line'; // Kembalikan ke line
    }
}
