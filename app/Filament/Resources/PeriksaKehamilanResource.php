<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PeriksaKehamilanResource\Pages;
use App\Models\PeriksaKehamilan;
use App\Models\Kehamilan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PeriksaKehamilanResource extends Resource
{
    protected static ?string $model            = PeriksaKehamilan::class;
    protected static ?string $navigationLabel  = 'Pemeriksaan Ibu Hamil';
    protected static ?string $modelLabel       = 'Data Pemeriksaan Ibu Hamil';
    protected static ?string $pluralModelLabel = 'Pemeriksaan Ibu Hamil';
    protected static ?string $navigationGroup  = 'Kesehatan Ibu & Anak';
    protected static ?int    $navigationSort   = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Kunjungan')
                    ->schema([
                        Forms\Components\Select::make('kehamilan_id')
                            ->label('Ibu Hamil')
                            ->options(fn () =>
                                Kehamilan::with('ibu')
                                    ->where('status', 'aktif')
                                    ->get()
                                    ->mapWithKeys(fn ($k) => [
                                        $k->id => $k->ibu?->nama . ' (HPHT: ' . \Carbon\Carbon::parse($k->hpht)->format('d/m/Y') . ')'
                                    ])
                            )
                            ->searchable()
                            ->required()
                            ->live(),
                        Forms\Components\Select::make('kader_id')
                            ->label('Kader')
                            ->options(
                                \App\Models\User::where('role', 'kader')->pluck('name', 'id')
                            )
                            ->searchable()
                            ->required(),
                        Forms\Components\DatePicker::make('tgl_periksa')
                            ->label('Tanggal Periksa')
                            ->displayFormat('d/m/Y')
                            ->maxDate(now())
                            ->required()
                            ->default(now()),
                        Forms\Components\Select::make('kunjungan_ke')
                            ->label('Kunjungan Ke')
                            ->options([
                                'K1' => 'K1 — Trimester 1 (0-12 minggu)',
                                'K2' => 'K2 — Trimester 2 (13-27 minggu)',
                                'K3' => 'K3 — Trimester 3 (28-35 minggu)',
                                'K4' => 'K4 — Trimester 3 (36+ minggu)',
                            ])
                            ->required()
                            ->native(false),
                    ])->columns(2),

                Forms\Components\Section::make('Hasil Pemeriksaan')
                    ->schema([
                        Forms\Components\TextInput::make('usia_kehamilan')
                            ->label('Usia Kehamilan')
                            ->numeric()
                            ->suffix('minggu')
                            ->minValue(1)
                            ->maxValue(42),
                        Forms\Components\TextInput::make('berat_badan')
                            ->label('Berat Badan')
                            ->numeric()
                            ->step(0.1)
                            ->suffix('kg'),
                        Forms\Components\TextInput::make('lila_cm')
                            ->label('LILA')
                            ->numeric()
                            ->step(0.1)
                            ->suffix('cm')
                            ->helperText('Lingkar Lengan Atas — Normal ≥ 23.5 cm'),
                        Forms\Components\TextInput::make('tensi_sistol')
                            ->label('Tensi Sistol')
                            ->numeric()
                            ->suffix('mmHg'),
                        Forms\Components\TextInput::make('tensi_diastol')
                            ->label('Tensi Diastol')
                            ->numeric()
                            ->suffix('mmHg'),
                        Forms\Components\TextInput::make('tablet_fe')
                            ->label('Tablet Fe')
                            ->numeric()
                            ->suffix('tablet')
                            ->helperText('Jumlah tablet Fe yang diberikan'),
                        Forms\Components\Select::make('status_gizi')
                            ->label('Status Gizi')
                            ->options([
                                'Normal' => 'Normal (LILA ≥ 23.5 cm)',
                                'KEK'    => 'KEK — Kekurangan Energi Kronik (LILA < 23.5 cm)',
                            ])
                            ->native(false),
                        Forms\Components\Textarea::make('catatan')
                            ->label('Catatan')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('tfu_cm')
                            ->label('TFU (cm)')
                            ->numeric()
                            ->step(0.1)
                            ->suffix('cm')
                            ->helperText('Tinggi Fundus Uteri'),

                        Forms\Components\TextInput::make('djj')
                            ->label('DJJ')
                            ->numeric()
                            ->suffix('bpm')
                            ->helperText('Normal: 120-160 bpm'),

                        Forms\Components\TextInput::make('hb')
                            ->label('HB (g/dL)')
                            ->numeric()
                            ->step(0.1)
                            ->suffix('g/dL')
                            ->helperText('Normal ≥ 11 g/dL'),

                        Forms\Components\Select::make('imunisasi_tt')
                            ->label('Imunisasi TT')
                            ->options([
                                'TT1' => 'TT1',
                                'TT2' => 'TT2',
                                'TT3' => 'TT3',
                                'TT4' => 'TT4',
                                'TT5' => 'TT5',
                            ])
                            ->placeholder('Tidak ada')
                            ->native(false),

                        Forms\Components\Toggle::make('edema')
                            ->label('Edema')
                            ->helperText('Ada pembengkakan kaki/tangan?')
                            ->default(false),

                        Forms\Components\Select::make('protein_urin')
                            ->label('Protein Urin')
                            ->options([
                                'Negatif'    => 'Negatif',
                                'Positif +1' => 'Positif +1',
                                'Positif +2' => 'Positif +2',
                                'Positif +3' => 'Positif +3',
                            ])
                            ->placeholder('Tidak diperiksa')
                            ->native(false),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no')
                    ->label('No')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('kehamilan.ibu.posyandu.nama')
                    ->label('Posyandu')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kehamilan.ibu.nama')
                    ->label('Nama Ibu')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tgl_periksa')
                    ->label('Tgl Periksa')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('kunjungan_ke')
                    ->label('Kunjungan')
                    ->badge()
                    ->color(fn (string $state): string => match($state) {
                        'K1' => 'info',
                        'K2' => 'success',
                        'K3' => 'warning',
                        'K4' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('usia_kehamilan')
                    ->label('Usia')
                    ->formatStateUsing(fn ($state) => $state ? $state . ' mgg' : '-'),
                Tables\Columns\TextColumn::make('berat_badan')
                    ->label('BB (kg)'),
                Tables\Columns\TextColumn::make('lila_cm')
                    ->label('LILA (cm)'),
                Tables\Columns\TextColumn::make('tensi_sistol')
                    ->label('Tensi')
                    ->formatStateUsing(fn ($record) =>
                        $record->tensi_sistol && $record->tensi_diastol
                            ? $record->tensi_sistol . '/' . $record->tensi_diastol
                            : '-'
                    ),
                Tables\Columns\TextColumn::make('status_gizi')
                    ->label('Status Gizi')
                    ->badge()
                    ->color(fn (?string $state): string => match($state) {
                        'Normal' => 'success',
                        'KEK'    => 'danger',
                        default  => 'gray',
                    })
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('tfu_cm')
                    ->label('TFU')
                    ->formatStateUsing(fn ($state) => $state ? $state . ' cm' : '-')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('djj')
                    ->label('DJJ')
                    ->formatStateUsing(fn ($state) => $state ? $state . ' bpm' : '-')
                    ->color(fn ($state) => $state && ($state < 120 || $state > 160) ? 'danger' : 'gray')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('hb')
                    ->label('HB')
                    ->formatStateUsing(fn ($state) => $state ? $state . ' g/dL' : '-')
                    ->color(fn ($state) => $state && $state < 11 ? 'danger' : 'gray')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\IconColumn::make('edema')
                    ->label('Edema')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('kader.name')
                    ->label('Kader'),
            ])
            ->defaultSort('tgl_periksa', 'desc')
            ->searchPlaceholder('Cari nama ibu...')
            ->filters([
                Tables\Filters\SelectFilter::make('kunjungan_ke')
                    ->label('Kunjungan')
                    ->options([
                        'K1' => 'K1', 'K2' => 'K2',
                        'K3' => 'K3', 'K4' => 'K4',
                    ])
                    ->native(false),
                Tables\Filters\SelectFilter::make('status_gizi')
                    ->label('Status Gizi')
                    ->options([
                        'Normal' => 'Normal',
                        'KEK'    => 'KEK',
                    ])
                    ->native(false),
            ])
            ->filtersTriggerAction(
                fn (Tables\Actions\Action $action) => $action
                    ->button()
                    ->label('Filter')
                    ->icon('heroicon-o-funnel')
            )
            ->actions([
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->button()
                    ->color('danger')
                    ->icon(null),
            ])
            ->bulkActions([])
            ->paginated(false);
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
