<?php
namespace App\Filament\Kader\Pages;

use App\Filament\Pages\Laporan as BaseLaporan;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;

class Laporan extends BaseLaporan
{
    protected static ?string $navigationLabel = 'Laporan';
    protected static ?string $navigationIcon  = null;
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?int    $navigationSort  = 1;

    public function mount(): void
    {
        $this->posyandu_id = (string) auth()->user()->posyandu_id;
        $this->bulan       = (string) now()->month;
        $this->tahun       = (string) now()->year;
        $this->form->fill([
            'posyandu_id' => $this->posyandu_id,
            'bulan'       => $this->bulan,
            'tahun'       => $this->tahun,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('posyandu_id')
                    ->label('Posyandu')
                    ->options(\App\Models\Posyandu::where('id', auth()->user()->posyandu_id)->pluck('nama', 'id'))
                    ->default(auth()->user()->posyandu_id)
                    ->hidden()
                    ->native(false),

                Select::make('bulan')
                    ->label('Bulan')
                    ->options([
                        '1'=>'Januari','2'=>'Februari','3'=>'Maret','4'=>'April',
                        '5'=>'Mei','6'=>'Juni','7'=>'Juli','8'=>'Agustus',
                        '9'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember',
                    ])
                    ->hidden(fn () => $this->activeTab === 'kehamilan')
                    ->native(false),

                Select::make('tahun')
                    ->label('Tahun')
                    ->options(collect(range(now()->year, now()->year - 3))
                        ->mapWithKeys(fn ($y) => [$y => $y])
                        ->toArray()
                    )
                    ->native(false),
            ])
            ->columns(2);
    }
}
