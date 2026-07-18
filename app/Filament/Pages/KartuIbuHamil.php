<?php

namespace App\Filament\Pages;

use App\Models\Ibu;
use App\Models\Posyandu;
use App\Models\Kehamilan;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;

class KartuIbuHamil extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationLabel = 'Kartu Ibu Hamil';
    protected static ?string $navigationIcon  = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Kesehatan Ibu & Anak';
    protected static ?string $title           = 'Kartu Ibu Hamil';
    protected static ?int    $navigationSort  = 4;
    protected static string  $view            = 'filament.pages.kartu-ibu-hamil';

    public ?string $posyandu_id = null;
    public ?string $ibu_id      = null;

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
                    ->afterStateUpdated(fn () => $this->ibu_id = null),

                Select::make('ibu_id')
                    ->label('Nama Ibu')
                    ->options(fn () =>
                        $this->posyandu_id
                            ? \App\Models\Ibu::where('posyandu_id', $this->posyandu_id)
                                ->whereHas('kehamilan')
                                ->pluck('nama', 'id')
                            : []
                    )
                    ->placeholder('Pilih Ibu')
                    ->native(false)
                    ->live()
                    ->helperText('Hanya menampilkan ibu yang memiliki riwayat kehamilan'),
            ])
            ->columns(2);
    }

    public function getIbuData(): array
    {
        if (!$this->ibu_id) return [];

        $ibu = Ibu::find($this->ibu_id);
        if (!$ibu) return [];

        $kehamilan = Kehamilan::with(['periksaKehamilan.kader'])
            ->where('ibu_id', $this->ibu_id)
            ->orderBy('hpht', 'desc')
            ->get();

        $posyandu = Posyandu::find($this->posyandu_id);
        $kader    = \App\Models\User::where('posyandu_id', $this->posyandu_id)
            ->where('role', 'kader')
            ->first();

        return [
            'ibu' => [
                'nama'     => $ibu->nama,
                'nik'      => $ibu->nik ?? '-',
                'tgl_lahir'=> $ibu->tgl_lahir?->format('d/m/Y') ?? '-',
                'no_hp'    => $ibu->no_hp ?? '-',
                'goldar'   => $ibu->goldar ?? '-',
                'alamat'   => $ibu->alamat ?? '-',
                'posyandu' => $posyandu?->nama ?? '-',
                'kader'    => $kader?->name ?? '-',
            ],
            'kehamilan' => $kehamilan,
            'posyandu'  => $posyandu,
            'kader'     => $kader,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('cetakPdf')
                ->label('Cetak Kartu')
                ->icon('heroicon-o-printer')
                ->color('primary')
                ->visible(fn () => $this->ibu_id !== null)
                ->action(function () {
                    $data = $this->getIbuData();
                    if (empty($data)) return;

                    $pdf = Pdf::loadView('laporan.kartu-ibu-hamil-pdf', $data)
                        ->setPaper('a4', 'portrait');

                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        'Kartu-Ibu-Hamil-' . str_replace(' ', '-', $data['ibu']['nama']) . '.pdf'
                    );
                }),
        ];
    }
}
