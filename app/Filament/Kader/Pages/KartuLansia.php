<?php
namespace App\Filament\Kader\Pages;
use App\Models\Lansia;
use App\Models\Posyandu;
use App\Models\PeriksaLansia;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;

class KartuLansia extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationLabel = 'Kartu Kesehatan Lansia';
    protected static ?string $navigationIcon  = null;
    protected static ?string $navigationGroup = 'Kesehatan Lansia';
    protected static ?string $title           = 'Kartu Kesehatan Lansia';
    protected static ?int    $navigationSort  = 3;
    protected static string  $view            = 'filament.pages.kartu-lansia';

    public ?string $posyandu_id = null;
    public ?string $lansia_id   = null;

    public function mount(): void
    {
        $this->posyandu_id = (string) auth()->user()->posyandu_id;
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Select::make('lansia_id')
                ->label('Nama Lansia')
                ->options(fn () => \App\Models\Lansia::where('posyandu_id', auth()->user()->posyandu_id)->pluck('nama', 'id'))
                ->placeholder('Pilih Lansia')
                ->native(false)
                ->live(),
        ])->columns(1);
    }

    public function getLansiaData(): array
    {
        if (!$this->lansia_id) return [];
        $lansia   = Lansia::find($this->lansia_id);
        if (!$lansia) return [];
        $riwayat  = PeriksaLansia::with(['kader'])->where('lansia_id', $this->lansia_id)->orderBy('tgl_periksa')->get();
        $posyandu = Posyandu::find($this->posyandu_id);
        $kader    = auth()->user();
        return [
            'lansia' => [
                'nama'             => $lansia->nama,
                'jk'               => $lansia->jk === 'L' ? 'Laki-laki' : 'Perempuan',
                'tgl_lahir'        => $lansia->tgl_lahir?->format('d/m/Y') ?? '-',
                'alamat'           => $lansia->alamat ?? '-',
                'no_hp'            => $lansia->no_hp ?? '-',
                'riwayat_penyakit' => $lansia->riwayat_penyakit ?? '-',
                'posyandu'         => $posyandu?->nama ?? '-',
                'kader'            => $kader->name,
            ],
            'riwayat'  => $riwayat,
            'posyandu' => $posyandu,
            'kader'    => $kader,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('cetakPdf')
                ->label('Cetak Kartu')
                ->icon('heroicon-o-printer')
                ->color('primary')
                ->visible(fn () => $this->lansia_id !== null)
                ->action(function () {
                    $data = $this->getLansiaData();
                    if (empty($data)) return;
                    $pdf = Pdf::loadView('laporan.kartu-lansia-pdf', $data)->setPaper('a4', 'portrait');
                    return response()->streamDownload(fn () => print($pdf->output()), 'Kartu-Lansia-' . str_replace(' ', '-', $data['lansia']['nama']) . '.pdf');
                }),
        ];
    }
}
