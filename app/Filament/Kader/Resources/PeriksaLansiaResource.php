<?php
namespace App\Filament\Kader\Resources;
use App\Filament\Kader\Resources\PeriksaLansiaResource\Pages;
use App\Models\Lansia;
use App\Models\User;
use Filament\Forms;

class PeriksaLansiaResource extends \App\Filament\Resources\PeriksaLansiaResource
{
    protected static ?string $navigationGroup = 'Kesehatan Lansia';
    protected static ?int    $navigationSort  = 2;

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('posyandu_id', auth()->user()->posyandu_id);
    }

    public static function form(\Filament\Forms\Form $form): \Filament\Forms\Form
    {
        $posyanduId = auth()->user()->posyandu_id;
        return parent::form($form)->schema([
            Forms\Components\Section::make('Informasi Pemeriksaan')
                ->schema([
                    Forms\Components\Hidden::make('posyandu_id')->default($posyanduId),
                    Forms\Components\Select::make('lansia_id')
                        ->label('Nama Lansia')
                        ->options(\App\Models\Lansia::where('posyandu_id', $posyanduId)->pluck('nama', 'id'))
                        ->searchable()->required()->native(false),
                    Forms\Components\Select::make('kader_id')
                        ->label('Kader')
                        ->options(\App\Models\User::where('role', 'kader')->where('posyandu_id', $posyanduId)->pluck('name', 'id'))
                        ->default(auth()->user()->id)
                        ->searchable()->required()->native(false),
                    Forms\Components\DatePicker::make('tgl_periksa')
                        ->label('Tanggal Periksa')
                        ->displayFormat('d/m/Y')->maxDate(now())->required()->default(now()),
                ])->columns(2),

            Forms\Components\Section::make('Antropometri')
                ->schema([
                    Forms\Components\TextInput::make('berat_kg')
                        ->label('Berat Badan (kg)')->numeric()->step(0.1)->suffix('kg')->required(),
                    Forms\Components\TextInput::make('tinggi_cm')
                        ->label('Tinggi Badan (cm)')->numeric()->step(0.1)->suffix('cm')->required(),
                    Forms\Components\TextInput::make('imt')
                        ->label('IMT (otomatis)')->numeric()->suffix('kg/m²')
                        ->disabled()->dehydrated(false)
                        ->helperText('Dihitung otomatis saat disimpan'),
                    Forms\Components\TextInput::make('lingkar_perut')
                        ->label('Lingkar Perut (cm)')->numeric()->step(0.1)->suffix('cm'),
                ])->columns(2),

            Forms\Components\Section::make('Vital Sign')
                ->schema([
                    Forms\Components\TextInput::make('tensi_sistol')
                        ->label('Tensi Sistol')->numeric()->suffix('mmHg'),
                    Forms\Components\TextInput::make('tensi_diastol')
                        ->label('Tensi Diastol')->numeric()->suffix('mmHg'),
                    Forms\Components\TextInput::make('gula_darah')
                        ->label('Gula Darah')->numeric()->suffix('mg/dL'),
                    Forms\Components\TextInput::make('kolesterol')
                        ->label('Kolesterol')->numeric()->suffix('mg/dL'),
                    Forms\Components\TextInput::make('asam_urat')
                        ->label('Asam Urat')->numeric()->suffix('mg/dL'),
                    Forms\Components\TextInput::make('spo2')
                        ->label('Saturasi Oksigen (SpO2)')->numeric()->suffix('%')
                        ->helperText('Normal ≥ 95%'),
                    Forms\Components\TextInput::make('nadi')
                        ->label('Denyut Nadi')->numeric()->suffix('bpm')
                        ->helperText('Normal 60-100 bpm'),
                    Forms\Components\Textarea::make('obat_rutin')
                        ->label('Obat Rutin')->rows(2)->columnSpanFull(),
                    Forms\Components\Textarea::make('keluhan')
                        ->label('Keluhan')->rows(2)->columnSpanFull(),
                ])->columns(2),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPeriksaLansias::route('/'),
            'create' => Pages\CreatePeriksaLansia::route('/create'),
            'edit'   => Pages\EditPeriksaLansia::route('/{record}/edit'),
        ];
    }
}
