<?php

namespace App\Filament\Pages;

use App\Models\Anak;
use App\Models\Posyandu;
use App\Models\TimbangBalita;
use App\Models\ZscoreReferensi;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;

class GrafikKMS extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationLabel = 'Grafik KMS';
    protected static ?string $navigationIcon  = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?string $title           = 'Grafik KMS';
    protected static ?int    $navigationSort  = 6;
    protected static string  $view            = 'filament.pages.grafik-k-m-s';

    public ?string $posyandu_id = null;
    public ?string $anak_id     = null;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('posyandu_id')
                    ->label('Posyandu')
                    ->options(Posyandu::where('is_active', true)->pluck('nama', 'id'))
                    ->placeholder('Pilih Posyandu')
                    ->native(false)
                    ->live()
                    ->afterStateUpdated(fn () => $this->anak_id = null),

                Select::make('anak_id')
                    ->label('Nama Anak')
                    ->options(fn () =>
                        $this->posyandu_id
                            ? Anak::where('posyandu_id', $this->posyandu_id)->pluck('nama', 'id')
                            : []
                    )
                    ->placeholder('Pilih Anak')
                    ->native(false)
                    ->live()
                    ->helperText('Pilih posyandu terlebih dahulu'),
            ])
            ->columns(2);
    }

    // public function getChartData(): array
    // {
    //     if (!$this->anak_id) return [];

    //     $anak    = Anak::find($this->anak_id);
    //     if (!$anak) return [];

    //     $timbang = TimbangBalita::with('hasilGizi')
    //         ->where('anak_id', $this->anak_id)
    //         ->orderBy('tgl_periksa')
    //         ->get();

    //     if ($timbang->isEmpty()) return [];

    //     // Hitung umur saat timbang
    //     $dataPoints = $timbang->map(fn ($t) => [
    //         'x'     => (int) $anak->tgl_lahir->diffInMonths($t->tgl_periksa),
    //         'y'     => (float) $t->berat_kg,
    //         'label' => $t->tgl_periksa->format('d/m/Y'),
    //     ])->toArray();

    //     $minUsia = max(0, collect($dataPoints)->min('x') - 1);
    //     $maxUsia = min(60, collect($dataPoints)->max('x') + 1);

    //     // Ambil referensi WHO BB/U
    //     $referensi = ZscoreReferensi::where('jenis', 'BB/U')
    //         ->where('jk', $anak->jk)
    //         ->whereBetween('usia_bulan', [$minUsia, $maxUsia])
    //         ->orderBy('usia_bulan')
    //         ->get();

    //     $labels   = $referensi->pluck('usia_bulan')->map(fn ($u) => $u . ' bln')->toArray();
    //     $refUsia  = $referensi->pluck('usia_bulan')->toArray();

    //     return [
    //         'labels'     => $labels,
    //         'refUsia'    => $refUsia,
    //         'dataPoints' => $dataPoints,
    //         'sd3neg'     => $referensi->pluck('sd_min3')->toArray(),
    //         'sd2neg'     => $referensi->pluck('sd_min2')->toArray(),
    //         'median'     => $referensi->pluck('median')->toArray(),
    //         'sd2pos'     => $referensi->pluck('sd_plus2')->toArray(),
    //         'sd3pos'     => $referensi->pluck('sd_plus3')->toArray(),
    //         'anak'       => [
    //             'nama'    => $anak->nama,
    //             'jk'      => $anak->jk === 'L' ? 'Laki-laki' : 'Perempuan',
    //             'lahir'   => $anak->tgl_lahir->format('d/m/Y'),
    //             'ibu'     => $anak->ibu?->nama ?? '-',
    //         ],
    //     ];
    // }

    public function getChartData(): array
    {
        if (!$this->anak_id) return [];

        $anak = Anak::with('posyandu')->find($this->anak_id);
        if (!$anak) return [];

        $posyandu = \App\Models\Posyandu::find($this->posyandu_id);

        $kader = User::where('posyandu_id', $this->posyandu_id)
                     ->where('role', 'kader')
                     ->first();

        $timbang = TimbangBalita::with('hasilGizi')
            ->where('anak_id', $this->anak_id)
            ->orderBy('tgl_periksa')
            ->get();

        if ($timbang->isEmpty()) return [];

        // Hitung umur saat timbang
        $dataPoints = $timbang->map(fn ($t) => [
            'x'     => (int) $anak->tgl_lahir->diffInMonths($t->tgl_periksa),
            'y'     => (float) $t->berat_kg,
            'label' => $t->tgl_periksa->format('d/m/Y'),
        ])->toArray();

        $minUsia = max(0, collect($dataPoints)->min('x') - 1);
        $maxUsia = min(60, collect($dataPoints)->max('x') + 1);

        // Ambil referensi WHO BB/U
        $referensi = ZscoreReferensi::where('jenis', 'BB/U')
            ->where('jk', $anak->jk)
            ->whereBetween('usia_bulan', [$minUsia, $maxUsia])
            ->orderBy('usia_bulan')
            ->get();

        $labels   = $referensi->pluck('usia_bulan')->map(fn ($u) => $u . ' bln')->toArray();
        $refUsia  = $referensi->pluck('usia_bulan')->toArray();

        return [
            'labels'     => $labels,
            'refUsia'    => $refUsia,
            'dataPoints' => $dataPoints,
            'sd3neg'     => $referensi->pluck('sd_min3')->toArray(),
            'sd2neg'     => $referensi->pluck('sd_min2')->toArray(),
            'median'     => $referensi->pluck('median')->toArray(),
            'sd2pos'     => $referensi->pluck('sd_plus2')->toArray(),
            'sd3pos'     => $referensi->pluck('sd_plus3')->toArray(),
            'anak' => [
                'nama'     => $anak->nama,
                'jk'       => $anak->jk === 'L' ? 'Laki-laki' : 'Perempuan',
                'lahir'    => $anak->tgl_lahir->format('d/m/Y'),
                'ibu'      => $anak->ibu?->nama ?? '-',
                'posyandu' => $posyandu?->nama ?? '-',
                'kader'    => $kader?->name ?? '-',
            ],
            // TAMBAHKAN DATA POSYANDU & KADER DI SINI
            'posyandu' => [
                'nama'  => $anak->posyandu?->nama ?? '-',
                'kader' => $anak->posyandu?->nama_kader ?? '.............................................',

            ]
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportPdf')
                ->label('Print KMS')
                ->icon('heroicon-o-printer')
                ->color('primary')
                ->visible(fn () => $this->anak_id !== null)
                ->action(function () {
                    $chart = $this->getChartData();
                    if (empty($chart)) return;

                    $timbangList = \App\Models\TimbangBalita::with(['hasilGizi'])
                        ->where('anak_id', $this->anak_id)
                        ->orderBy('tgl_periksa')
                        ->get();

                    $posyandu = \App\Models\Posyandu::find($this->posyandu_id);

                    $kader = \App\Models\User::where('posyandu_id', $this->posyandu_id)
                        ->where('role', 'kader')
                        ->first();

                    $pdf = Pdf::loadView('laporan.kms-pdf', [
                        'chart'       => $chart,
                        'timbangList' => $timbangList,
                        'posyandu'    => $posyandu,
                        'kader'       => $kader,
                    ])->setPaper('a4', 'portrait');

                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        'KMS-' . str_replace(' ', '-', $chart['anak']['nama']) . '.pdf'
                    );
                }),
        ];
    }
}
