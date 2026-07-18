<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PeriksaLansiaResource\Pages;
use App\Models\PeriksaLansia;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PeriksaLansiaResource extends Resource
{
    protected static ?string $model            = PeriksaLansia::class;
    protected static ?string $navigationLabel  = 'Pemeriksaan Lansia';
    protected static ?string $modelLabel       = 'Data Pemeriksaan Lansia';
    protected static ?string $pluralModelLabel = 'Pemeriksaan Lansia';
    protected static ?string $navigationGroup  = 'Kesehatan Lansia';
    protected static ?int    $navigationSort   = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pemeriksaan')
                    ->schema([
                        Forms\Components\Select::make('posyandu_id')
                            ->label('Posyandu')
                            ->relationship('posyandu', 'nama')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Forms\Set $set) {
                                $set('lansia_id', null);
                                $set('kader_id', null);
                            }),
                        Forms\Components\Select::make('lansia_id')
                            ->label('Nama Lansia')
                            ->options(fn (Forms\Get $get) =>
                                \App\Models\Lansia::where('posyandu_id', $get('posyandu_id'))
                                    ->pluck('nama', 'id')
                            )
                            ->searchable()
                            ->required()
                            ->live()
                            ->helperText('Pilih posyandu terlebih dahulu'),
                        Forms\Components\Select::make('kader_id')
                            ->label('Kader')
                            ->options(fn (Forms\Get $get) =>
                                \App\Models\User::where('role', 'kader')
                                    ->where('posyandu_id', $get('posyandu_id'))
                                    ->pluck('name', 'id')
                            )
                            ->searchable()
                            ->required()
                            ->live()
                            ->helperText('Pilih posyandu terlebih dahulu'),
                        Forms\Components\DatePicker::make('tgl_periksa')
                            ->label('Tanggal Periksa')
                            ->displayFormat('d/m/Y')
                            ->maxDate(now())
                            ->required()
                            ->default(now()),
                    ])->columns(2),

                Forms\Components\Section::make('Antropometri')
                    ->schema([
                        Forms\Components\TextInput::make('berat_kg')
                            ->label('Berat Badan (kg)')
                            ->numeric()
                            ->step(0.1)
                            ->suffix('kg')
                            ->required(),
                        Forms\Components\TextInput::make('tinggi_cm')
                            ->label('Tinggi Badan (cm)')
                            ->numeric()
                            ->step(0.1)
                            ->suffix('cm')
                            ->required(),
                        Forms\Components\TextInput::make('imt')
                            ->label('IMT (otomatis)')
                            ->numeric()
                            ->suffix('kg/m²')
                            ->disabled()
                            ->dehydrated(false)
                            ->helperText('Dihitung otomatis saat disimpan'),
                        Forms\Components\TextInput::make('lingkar_perut')
                            ->label('Lingkar Perut (cm)')
                            ->numeric()
                            ->step(0.1)
                            ->suffix('cm'),
                    ])->columns(2),

                Forms\Components\Section::make('Vital Sign')
                    ->schema([
                        Forms\Components\TextInput::make('tensi_sistol')
                            ->label('Tensi Sistol')
                            ->numeric()
                            ->suffix('mmHg'),
                        Forms\Components\TextInput::make('tensi_diastol')
                            ->label('Tensi Diastol')
                            ->numeric()
                            ->suffix('mmHg'),
                        Forms\Components\TextInput::make('gula_darah')
                            ->label('Gula Darah Sewaktu')
                            ->numeric()
                            ->step(0.1)
                            ->suffix('mg/dL'),
                        Forms\Components\TextInput::make('kolesterol')
                            ->label('Kolesterol')
                            ->numeric()
                            ->step(0.1)
                            ->suffix('mg/dL'),
                        Forms\Components\TextInput::make('asam_urat')
                            ->label('Asam Urat')
                            ->numeric()
                            ->step(0.1)
                            ->suffix('mg/dL'),
                        Forms\Components\TextInput::make('spo2')
                            ->label('Saturasi Oksigen (SpO2)')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->suffix('%')
                            ->helperText('Normal ≥ 95%'),

                        Forms\Components\TextInput::make('nadi')
                            ->label('Denyut Nadi')
                            ->numeric()
                            ->suffix('bpm')
                            ->helperText('Normal 60-100 bpm'),
                    ])->columns(2),

                Forms\Components\Section::make('Catatan')
                    ->schema([
                        Forms\Components\Textarea::make('obat_rutin')
                            ->label('Obat Rutin')
                            ->rows(2)
                            ->placeholder('Contoh: Amlodipin 5mg, Metformin 500mg')
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('keluhan')
                            ->label('Keluhan')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no')
                    ->label('No')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('posyandu.nama')
                    ->label('Posyandu')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lansia.nama')
                    ->label('Nama Lansia')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tgl_periksa')
                    ->label('Tgl Periksa')
                    ->date('d/m/Y'),
                Tables\Columns\TextColumn::make('berat_kg')
                    ->label('Berat (kg)')
                    ->numeric(decimalPlaces: 1)
                    ->suffix(' kg'),
                Tables\Columns\TextColumn::make('tinggi_cm')
                    ->label('Tinggi (cm)')
                    ->numeric(decimalPlaces: 1)
                    ->suffix(' cm'),
                Tables\Columns\TextColumn::make('imt')
                    ->label('IMT')
                    ->numeric(decimalPlaces: 1)
                    ->badge()
                    ->color(fn (?string $state): string => match(true) {
                        $state === null         => 'gray',
                        (float)$state < 18.5   => 'warning',
                        (float)$state <= 24.9  => 'success',
                        (float)$state <= 29.9  => 'warning',
                        default                => 'danger',
                    }),
                Tables\Columns\TextColumn::make('tensi_sistol')
                    ->label('Tensi')
                    ->formatStateUsing(fn ($record) =>
                        $record->tensi_sistol && $record->tensi_diastol
                            ? $record->tensi_sistol . '/' . $record->tensi_diastol
                            : '-'
                    ),
                Tables\Columns\TextColumn::make('spo2')
                    ->label('SpO2')
                    ->formatStateUsing(fn ($state) => $state ? $state . '%' : '-')
                    ->color(fn ($state) => $state && $state < 95 ? 'danger' : 'gray')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('nadi')
                    ->label('Nadi')
                    ->formatStateUsing(fn ($state) => $state ? $state . ' bpm' : '-')
                    ->color(fn ($state) => $state && ($state < 60 || $state > 100) ? 'danger' : 'gray')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('kader.name')
                    ->label('Kader'),
            ])
            ->defaultSort('tgl_periksa', 'desc')
            ->searchPlaceholder('Cari nama lansia...')
            ->filters([
                Tables\Filters\SelectFilter::make('posyandu_id')
                    ->label('Posyandu')
                    ->relationship('posyandu', 'nama'),
            ])
            ->filtersTriggerAction(
                fn (Tables\Actions\Action $action) => $action
                    ->button()
                    ->label('Filter')
                    ->icon('heroicon-o-funnel')
            )
            ->persistFiltersInSession()
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edit Data')
                    ->button()
                    ->color('warning')
                    ->icon(null),
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
            'index'  => Pages\ListPeriksaLansias::route('/'),
            'create' => Pages\CreatePeriksaLansia::route('/create'),
            'edit'   => Pages\EditPeriksaLansia::route('/{record}/edit'),
        ];
    }
}
