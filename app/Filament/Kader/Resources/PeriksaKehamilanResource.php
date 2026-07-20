<?php
namespace App\Filament\Kader\Resources;
use App\Filament\Kader\Resources\PeriksaKehamilanResource\Pages;
use App\Models\Kehamilan;
use App\Models\User;
use Filament\Forms;

class PeriksaKehamilanResource extends \App\Filament\Resources\PeriksaKehamilanResource
{
    protected static ?string $navigationGroup = 'Kesehatan Ibu & Anak';
    protected static ?int    $navigationSort  = 3;

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->whereHas('kehamilan.ibu', fn($q) => $q->where('posyandu_id', auth()->user()->posyandu_id));
    }

    public static function form(\Filament\Forms\Form $form): \Filament\Forms\Form
    {
        $posyanduId = auth()->user()->posyandu_id;
        return parent::form($form)->schema([
            Forms\Components\Section::make('Data Kunjungan')
                ->schema([
                    Forms\Components\Select::make('kehamilan_id')
                        ->label('Ibu Hamil')
                        ->options(fn () =>
                            \App\Models\Kehamilan::with('ibu')
                                ->where('status', 'aktif')
                                ->whereHas('ibu', fn($q) => $q->where('posyandu_id', $posyanduId))
                                ->get()
                                ->mapWithKeys(fn ($k) => [
                                    $k->id => $k->ibu?->nama . ' (HPHT: ' . \Carbon\Carbon::parse($k->hpht)->format('d/m/Y') . ')'
                                ])
                        )
                        ->searchable()->required()->native(false),
                    Forms\Components\Select::make('kader_id')
                        ->label('Kader')
                        ->options(\App\Models\User::where('role', 'kader')->where('posyandu_id', $posyanduId)->pluck('name', 'id'))
                        ->default(auth()->user()->id)
                        ->searchable()->required()->native(false),
                    Forms\Components\DatePicker::make('tgl_periksa')
                        ->label('Tanggal Periksa')
                        ->displayFormat('d/m/Y')->maxDate(now())->required()->default(now()),
                    Forms\Components\Select::make('kunjungan_ke')
                        ->label('Kunjungan Ke')
                        ->options([
                            'K1' => 'K1 — Trimester 1 (0-12 minggu)',
                            'K2' => 'K2 — Trimester 2 (13-27 minggu)',
                            'K3' => 'K3 — Trimester 3 (28-35 minggu)',
                            'K4' => 'K4 — Trimester 3 (36+ minggu)',
                        ])
                        ->required()->native(false),
                ])->columns(2),

            Forms\Components\Section::make('Hasil Pemeriksaan')
                ->schema([
                    Forms\Components\TextInput::make('usia_kehamilan')
                        ->label('Usia Kehamilan')->numeric()->suffix('minggu')->minValue(1)->maxValue(42),
                    Forms\Components\TextInput::make('berat_badan')
                        ->label('Berat Badan')->numeric()->step(0.1)->suffix('kg'),
                    Forms\Components\TextInput::make('lila_cm')
                        ->label('LILA')->numeric()->step(0.1)->suffix('cm')
                        ->helperText('Lingkar Lengan Atas — Normal ≥ 23.5 cm'),
                    Forms\Components\TextInput::make('tfu_cm')
                        ->label('TFU (cm)')->numeric()->step(0.1)->suffix('cm')
                        ->helperText('Tinggi Fundus Uteri'),
                    Forms\Components\TextInput::make('djj')
                        ->label('DJJ')->numeric()->suffix('bpm')
                        ->helperText('Normal: 120-160 bpm'),
                    Forms\Components\TextInput::make('hb')
                        ->label('HB (g/dL)')->numeric()->step(0.1)->suffix('g/dL')
                        ->helperText('Normal ≥ 11 g/dL'),
                    Forms\Components\TextInput::make('tensi_sistol')
                        ->label('Tensi Sistol')->numeric()->suffix('mmHg'),
                    Forms\Components\TextInput::make('tensi_diastol')
                        ->label('Tensi Diastol')->numeric()->suffix('mmHg'),
                    Forms\Components\TextInput::make('tablet_fe')
                        ->label('Tablet Fe')->numeric()->suffix('tablet')
                        ->helperText('Jumlah tablet Fe yang diberikan'),
                    Forms\Components\Select::make('imunisasi_tt')
                        ->label('Imunisasi TT')
                        ->options(['TT1'=>'TT1','TT2'=>'TT2','TT3'=>'TT3','TT4'=>'TT4','TT5'=>'TT5'])
                        ->placeholder('Tidak ada')->native(false),
                    Forms\Components\Select::make('status_gizi')
                        ->label('Status Gizi')
                        ->options([
                            'Normal' => 'Normal (LILA ≥ 23.5 cm)',
                            'KEK'    => 'KEK — Kekurangan Energi Kronik (LILA < 23.5 cm)',
                        ])
                        ->native(false),
                    Forms\Components\Toggle::make('edema')
                        ->label('Edema')->default(false),
                    Forms\Components\Select::make('protein_urin')
                        ->label('Protein Urin')
                        ->options([
                            'Negatif'    => 'Negatif',
                            'Positif +1' => 'Positif +1',
                            'Positif +2' => 'Positif +2',
                            'Positif +3' => 'Positif +3',
                        ])
                        ->placeholder('Tidak diperiksa')->native(false),
                    Forms\Components\Textarea::make('catatan')
                        ->label('Catatan')->rows(3)->columnSpanFull(),
                ])->columns(2),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPeriksaKehamilans::route('/'),
            'create' => Pages\CreatePeriksaKehamilan::route('/create'),
            'edit'   => Pages\EditPeriksaKehamilan::route('/{record}/edit'),
        ];
    }
}
