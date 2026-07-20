<?php
namespace App\Filament\Kader\Pages;
use App\Models\Anak;
use App\Models\Posyandu;
use App\Models\ZscoreReferensi;
use App\Models\TimbangBalita;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;

class GrafikKMS extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationLabel = 'Grafik KMS';
    protected static ?string $navigationIcon  = null;
    protected static ?string $navigationGroup = 'Kesehatan Ibu & Anak';
    protected static ?string $title           = 'Grafik KMS';
    protected static ?int    $navigationSort  = 11;
    protected static string  $view            = 'filament.pages.grafik-k-m-s';

    public ?string $posyandu_id = null;
    public ?string $anak_id     = null;

    public function mount(): void
    {
        $this->posyandu_id = (string) auth()->user()->posyandu_id;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('anak_id')
                    ->label('Nama Anak')
                    ->options(fn () => Anak::where('posyandu_id', auth()->user()->posyandu_id)->pluck('nama', 'id'))
                    ->placeholder('Pilih Anak')
                    ->native(false)
                    ->live(),
            ])
            ->columns(1);
    }

    public function getChartData(): array
    {
        if (!$this->anak_id) return [];

        $anak = Anak::find($this->anak_id);
        if (!$anak) return [];

        $timbang = TimbangBalita::with('hasilGizi')
            ->where('anak_id', $this->anak_id)
            ->orderBy('tgl_periksa')
            ->get();

        if ($timbang->isEmpty()) return [];

        $dataPoints = $timbang->map(fn ($t) => [
            'x'     => (int) $anak->tgl_lahir->diffInMonths($t->tgl_periksa),
            'y'     => (float) $t->berat_kg,
            'label' => $t->tgl_periksa->format('d/m/Y'),
        ])->toArray();

        $minUsia = max(0, collect($dataPoints)->min('x') - 1);
        $maxUsia = min(60, collect($dataPoints)->max('x') + 1);

        $referensi = ZscoreReferensi::where('jenis', 'BB/U')
            ->where('jk', $anak->jk)
            ->whereBetween('usia_bulan', [$minUsia, $maxUsia])
            ->orderBy('usia_bulan')
            ->get();

        $posyandu = Posyandu::find($this->posyandu_id);
        $kader    = auth()->user();

        return [
            'labels'     => $referensi->pluck('usia_bulan')->map(fn ($u) => $u . ' bln')->toArray(),
            'refUsia'    => $referensi->pluck('usia_bulan')->toArray(),
            'dataPoints' => $dataPoints,
            'sd3neg'     => $referensi->pluck('sd_min3')->toArray(),
            'sd2neg'     => $referensi->pluck('sd_min2')->toArray(),
            'median'     => $referensi->pluck('median')->toArray(),
            'sd2pos'     => $referensi->pluck('sd_plus2')->toArray(),
            'sd3pos'     => $referensi->pluck('sd_plus3')->toArray(),
            'anak'       => [
                'nama'     => $anak->nama,
                'jk'       => $anak->jk === 'L' ? 'Laki-laki' : 'Perempuan',
                'lahir'    => $anak->tgl_lahir->format('d/m/Y'),
                'ibu'      => $anak->ibu?->nama ?? '-',
                'posyandu' => $posyandu?->nama ?? '-',
                'kader'    => $kader->name,
            ],
            'posyandu'   => [
                'nama'  => $posyandu?->nama ?? '-',
                'kader' => $kader->name,
            ],
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

                    $timbangList = TimbangBalita::with(['hasilGizi'])
                        ->where('anak_id', $this->anak_id)
                        ->orderBy('tgl_periksa')
                        ->get();

                    $posyandu = \App\Models\Anak::with('posyandu')->find($this->anak_id)?->posyandu;

                    $pdf = Pdf::loadView('laporan.kms-pdf', [
                        'chart'       => $chart,
                        'timbangList' => $timbangList,
                        'posyandu'    => $posyandu,
                        'kecamatan'   => $posyandu?->kecamatan ?? '',
                        'kader'       => auth()->user(),
                    ])->setPaper('a4', 'portrait');

                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        'KMS-' . str_replace(' ', '-', $chart['anak']['nama']) . '.pdf'
                    );
                }),
        ];
    }
}
