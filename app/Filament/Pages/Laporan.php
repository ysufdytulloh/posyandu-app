<?php

namespace App\Filament\Pages;

use App\Models\Imunisasi;
use App\Models\HasilGizi;
use App\Models\PeriksaLansia;
use App\Models\PmtDistribusi;
use App\Models\Posyandu;
use App\Models\TimbangBalita;
use App\Models\VitaminA;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class Laporan extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationLabel = 'Laporan';
    protected static ?string $navigationIcon  = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $title           = 'Laporan';
    protected static ?int    $navigationSort  = 1;
    protected static string  $view            = 'filament.pages.laporan';

    public string  $activeTab    = 'timbang';
    public ?string $posyandu_id  = null;
    public ?string $bulan        = null;
    public ?string $tahun        = null;
    public bool    $showPreview  = false;

    public array $tabs = [
        'timbang'       => 'Timbang Balita',
        'imunisasi'     => 'Imunisasi',
        'vitamin_a'     => 'Vitamin A',
        'pmt'           => 'PMT Distribusi',
        'lansia'        => 'Pemeriksaan Lansia',
        'rekapitulasi'  => 'Rekapitulasi Desa',
    ];

    public function mount(): void
    {
        $this->bulan = (string) now()->month;
        $this->tahun = (string) now()->year;

        $this->form->fill([
            'posyandu_id' => null,
            'bulan'       => (string) now()->month,
            'tahun'       => (string) now()->year,
        ]);
    }

    public function setTab(string $tab): void
    {
        $this->activeTab   = $tab;
        $this->showPreview = false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('posyandu_id')
                    ->label('Posyandu')
                    ->options(Posyandu::where('is_active', true)->pluck('nama', 'id'))
                    ->placeholder('Semua Posyandu')
                    ->native(false),

                Select::make('bulan')
                    ->label('Bulan')
                    ->options([
                        '1' => 'Januari',   '2'  => 'Februari',
                        '3' => 'Maret',     '4'  => 'April',
                        '5' => 'Mei',       '6'  => 'Juni',
                        '7' => 'Juli',      '8'  => 'Agustus',
                        '9' => 'September', '10' => 'Oktober',
                        '11'=> 'November',  '12' => 'Desember',
                    ])
                    ->native(false),

                Select::make('tahun')
                    ->label('Tahun')
                    ->options(collect(range(now()->year, now()->year - 3))
                        ->mapWithKeys(fn ($y) => [$y => $y])
                        ->toArray()
                    )
                    ->native(false),
            ])
            ->columns(3);
    }

    public function tampilkan(): void
    {
        $this->showPreview = true;
    }

    public function getData(): array
    {
        $data = match($this->activeTab) {
            'timbang'=> TimbangBalita::with(['anak', 'posyandu', 'hasilGizi'])
                ->when($this->posyandu_id, fn($q) => $q->where('posyandu_id', $this->posyandu_id))
                ->when($this->bulan, fn($q) => $q->whereMonth('tgl_periksa', $this->bulan))
                ->when($this->tahun, fn($q) => $q->whereYear('tgl_periksa', $this->tahun))
                ->get(),

            'imunisasi' => Imunisasi::with(['anak', 'jenisImunisasi', 'kader'])
                ->when($this->posyandu_id, fn($q) => $q->whereHas('anak', fn($q) =>
                    $q->where('posyandu_id', $this->posyandu_id)))
                ->when($this->bulan, fn($q) => $q->whereMonth('tgl_imunisasi', $this->bulan))
                ->when($this->tahun, fn($q) => $q->whereYear('tgl_imunisasi', $this->tahun))
                ->get(),

            'vitamin_a' => VitaminA::with(['anak', 'posyandu'])
                ->when($this->posyandu_id, fn($q) => $q->where('posyandu_id', $this->posyandu_id))
                ->when($this->bulan, fn($q) => $q->whereMonth('tgl_distribusi', $this->bulan))
                ->when($this->tahun, fn($q) => $q->whereYear('tgl_distribusi', $this->tahun))
                ->get(),

            'pmt' => PmtDistribusi::with(['jenisPmt', 'posyandu', 'kader'])
                ->when($this->posyandu_id, fn($q) => $q->where('posyandu_id', $this->posyandu_id))
                ->when($this->bulan, fn($q) => $q->whereMonth('tgl_distribusi', $this->bulan))
                ->when($this->tahun, fn($q) => $q->whereYear('tgl_distribusi', $this->tahun))
                ->get(),

            'lansia' => PeriksaLansia::with(['lansia', 'posyandu', 'kader'])
                ->when($this->posyandu_id, fn($q) => $q->where('posyandu_id', $this->posyandu_id))
                ->when($this->bulan, fn($q) => $q->whereMonth('tgl_periksa', $this->bulan))
                ->when($this->tahun, fn($q) => $q->whereYear('tgl_periksa', $this->tahun))
                ->get(),

            'rekapitulasi' => Posyandu::withCount(['anak', 'lansia'])
                ->withCount([
                    'anak as total_timbang' => fn($q) => $q->whereHas('timbang'),
                    'anak as total_stunting' => fn($q) => $q->whereHas('timbang.hasilGizi', fn($q) =>
                        $q->whereIn('status_tbU', ['Pendek', 'Sangat Pendek'])
                    ),
                    'anak as total_gizi_kurang' => fn($q) => $q->whereHas('timbang.hasilGizi', fn($q) =>
                        $q->whereIn('status_bbU', ['Gizi Kurang', 'Gizi Buruk'])
                    ),
                ])
                ->when($this->posyandu_id, fn($q) => $q->where('id', $this->posyandu_id))
                ->get(),

            default => collect(),
        };

        return ['data' => $data];
    }

    public function resetFilter(): void
    {
        $this->posyandu_id  = null;
        $this->bulan        = (string) now()->month;
        $this->tahun        = (string) now()->year;
        $this->showPreview  = false;
        $this->form->fill([
            'posyandu_id' => null,
            'bulan'       => (string) now()->month,
            'tahun'       => (string) now()->year,
        ]);
    }

    public function exportPdf()
    {
        $data     = $this->getData();
        $posyandu = $this->posyandu_id
            ? \App\Models\Posyandu::find($this->posyandu_id)
            : null;

        $kader = $posyandu
            ? \App\Models\User::where('posyandu_id', $posyandu->id)
                ->where('role', 'kader')
                ->first()
            : null;

        // Ambil kecamatan dari data pertama jika semua posyandu
        $kecamatan = $posyandu?->kecamatan
            ?? $data['data']->first()?->posyandu?->kecamatan
            ?? \App\Models\Posyandu::first()?->kecamatan
            ?? 'Semua Posyandu';

        $pdf = Pdf::loadView('laporan.pdf', [
            'data'       => $data['data'],
            'jenis'      => $this->activeTab,
            'bulan'      => $this->bulan,
            'tahun'      => $this->tahun,
            'posyandu'   => $posyandu,
            'kader'      => $kader,
            'kecamatan'  => $kecamatan,
        ])->setPaper('a4', 'portrait');

        return response()->streamDownload(
            fn () => print($pdf->output()),
            'laporan-' . $this->activeTab . '-' . $this->bulan . '-' . $this->tahun . '.pdf'
        );
    }

    public function exportExcel()
    {
        return Excel::download(
            new \App\Exports\LaporanExport(
                $this->getData()['data'],
                $this->activeTab,
                $this->bulan,
                $this->tahun
            ),
            'laporan-' . $this->activeTab . '-' . $this->bulan . '-' . $this->tahun . '.xlsx'
        );
    }
}
