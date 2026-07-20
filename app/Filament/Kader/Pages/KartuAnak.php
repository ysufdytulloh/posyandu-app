<?php
namespace App\Filament\Kader\Pages;
use App\Models\Anak;
use App\Models\Posyandu;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;

class KartuAnak extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationLabel = 'Kartu Anak';
    protected static ?string $navigationIcon  = null;
    protected static ?string $navigationGroup = 'Kesehatan Ibu & Anak';
    protected static ?string $title           = 'Kartu Anak';
    protected static ?int    $navigationSort  = 12;
    protected static string  $view            = 'filament.pages.kartu-anak';

    public ?string $posyandu_id = null;
    public ?string $anak_id     = null;

    public function mount(): void
    {
        $this->posyandu_id = (string) auth()->user()->posyandu_id;
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Select::make('anak_id')
                ->label('Nama Anak')
                ->options(fn () => Anak::where('posyandu_id', auth()->user()->posyandu_id)->pluck('nama', 'id'))
                ->placeholder('Pilih Anak')
                ->native(false)
                ->live(),
        ])->columns(1);
    }

    public function getAnakData(): array
    {
        if (!$this->anak_id) return [];
        $anak     = Anak::with(['ibu', 'posyandu'])->find($this->anak_id);
        if (!$anak) return [];
        $posyandu = Posyandu::find($this->posyandu_id);
        $kader    = auth()->user();
        return [
            'anak' => [
                'nama'      => $anak->nama,
                'jk'        => $anak->jk === 'L' ? 'Laki-laki' : 'Perempuan',
                'tgl_lahir' => $anak->tgl_lahir?->format('d/m/Y') ?? '-',
                'ibu'       => $anak->ibu?->nama ?? '-',
                'posyandu'  => $posyandu?->nama ?? '-',
                'kader'     => $kader->name,
            ],
            'timbang'     => \App\Models\TimbangBalita::with('hasilGizi')->where('anak_id', $this->anak_id)->orderBy('tgl_periksa')->get(),
            'imunisasi'   => \App\Models\Imunisasi::with('jenisImunisasi')->where('anak_id', $this->anak_id)->orderBy('tgl_imunisasi')->get(),
            'sdidtk'      => \App\Models\Sdidtk::where('anak_id', $this->anak_id)->orderBy('tgl_periksa')->get(),
            'vitamin_a'   => \App\Models\VitaminA::where('anak_id', $this->anak_id)->orderBy('tgl_distribusi')->get(),
            'obat_cacing' => \App\Models\ObatCacing::where('anak_id', $this->anak_id)->orderBy('tgl_pemberian')->get(),
            'posyandu'    => $posyandu,
            'kader'       => $kader,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('cetakPdf')
                ->label('Cetak Kartu')
                ->icon('heroicon-o-printer')
                ->color('primary')
                ->visible(fn () => $this->anak_id !== null)
                ->action(function () {
                    $data = $this->getAnakData();
                    if (empty($data)) return;
                    $pdf = Pdf::loadView('laporan.kartu-anak-pdf', $data)->setPaper('a4', 'portrait');
                    return response()->streamDownload(fn () => print($pdf->output()), 'Kartu-Anak-' . str_replace(' ', '-', $data['anak']['nama']) . '.pdf');
                }),
        ];
    }
}
