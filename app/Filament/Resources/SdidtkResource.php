<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SdidtkResource\Pages;
use App\Models\Sdidtk;
use App\Models\Anak;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SdidtkResource extends Resource
{
    protected static ?string $model            = Sdidtk::class;
    protected static ?string $navigationLabel  = 'SDIDTK';
    protected static ?string $modelLabel       = 'Data SDIDTK';
    protected static ?string $pluralModelLabel = 'Data SDIDTK';
    protected static ?string $navigationGroup  = 'Kesehatan Ibu & Anak';
    protected static ?int    $navigationSort   = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Pemeriksaan')
                    ->schema([
                        Forms\Components\Select::make('posyandu_id')
                            ->label('Posyandu')
                            ->relationship('anak.posyandu', 'nama')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->dehydrated(false)
                            ->afterStateUpdated(fn (Forms\Set $set) => $set('anak_id', null)),
                        Forms\Components\Select::make('anak_id')
                            ->label('Nama Anak')
                            ->options(fn (Forms\Get $get) =>
                                Anak::when($get('posyandu_id'), fn ($q, $v) =>
                                    $q->where('posyandu_id', $v)
                                )->pluck('nama', 'id')
                            )
                            ->searchable()
                            ->required()
                            ->live()
                            ->helperText('Pilih posyandu terlebih dahulu'),
                        Forms\Components\Select::make('kader_id')
                            ->label('Kader')
                            ->options(fn (Forms\Get $get) =>
                                \App\Models\User::where('role', 'kader')
                                    ->pluck('name', 'id')
                            )
                            ->searchable()
                            ->required(),
                        Forms\Components\DatePicker::make('tgl_periksa')
                            ->label('Tanggal Periksa')
                            ->displayFormat('d/m/Y')
                            ->maxDate(now())
                            ->required()
                            ->default(now()),
                        Forms\Components\TextInput::make('usia_bulan')
                            ->label('Usia (bulan)')
                            ->numeric()
                            ->suffix('bulan')
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Penilaian SDIDTK')
                    ->description('S = Sesuai | M = Meragukan | P = Penyimpangan')
                    ->schema([
                        Forms\Components\Select::make('motorik_kasar')
                            ->label('Motorik Kasar')
                            ->options(['S' => 'S — Sesuai', 'M' => 'M — Meragukan', 'P' => 'P — Penyimpangan'])
                            ->required()
                            ->native(false),
                        Forms\Components\Select::make('motorik_halus')
                            ->label('Motorik Halus')
                            ->options(['S' => 'S — Sesuai', 'M' => 'M — Meragukan', 'P' => 'P — Penyimpangan'])
                            ->required()
                            ->native(false),
                        Forms\Components\Select::make('bicara_bahasa')
                            ->label('Bicara & Bahasa')
                            ->options(['S' => 'S — Sesuai', 'M' => 'M — Meragukan', 'P' => 'P — Penyimpangan'])
                            ->required()
                            ->native(false),
                        Forms\Components\Select::make('sosial_kemandirian')
                            ->label('Sosial & Kemandirian')
                            ->options(['S' => 'S — Sesuai', 'M' => 'M — Meragukan', 'P' => 'P — Penyimpangan'])
                            ->required()
                            ->native(false),
                        Forms\Components\Select::make('hasil')
                            ->label('Hasil Keseluruhan')
                            ->options([
                                'Normal'        => 'Normal — Semua sesuai',
                                'Suspek'        => 'Suspek — Ada yang meragukan',
                                'Penyimpangan'  => 'Penyimpangan — Ada penyimpangan',
                            ])
                            ->required()
                            ->native(false),
                        Forms\Components\Textarea::make('catatan')
                            ->label('Catatan')
                            ->rows(3)
                            ->columnSpanFull(),
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
                Tables\Columns\TextColumn::make('anak.posyandu.nama')
                    ->label('Posyandu')
                    ->searchable(),
                Tables\Columns\TextColumn::make('anak.nama')
                    ->label('Nama Anak')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tgl_periksa')
                    ->label('Tgl Periksa')
                    ->date('d/m/Y'),
                Tables\Columns\TextColumn::make('usia_bulan')
                    ->label('Usia')
                    ->formatStateUsing(fn ($state) => $state . ' bulan'),
                Tables\Columns\TextColumn::make('motorik_kasar')
                    ->label('MK')
                    ->badge()
                    ->color(fn ($state) => match($state) { 'S' => 'success', 'M' => 'warning', 'P' => 'danger', default => 'gray' }),
                Tables\Columns\TextColumn::make('motorik_halus')
                    ->label('MH')
                    ->badge()
                    ->color(fn ($state) => match($state) { 'S' => 'success', 'M' => 'warning', 'P' => 'danger', default => 'gray' }),
                Tables\Columns\TextColumn::make('bicara_bahasa')
                    ->label('BB')
                    ->badge()
                    ->color(fn ($state) => match($state) { 'S' => 'success', 'M' => 'warning', 'P' => 'danger', default => 'gray' }),
                Tables\Columns\TextColumn::make('sosial_kemandirian')
                    ->label('SK')
                    ->badge()
                    ->color(fn ($state) => match($state) { 'S' => 'success', 'M' => 'warning', 'P' => 'danger', default => 'gray' }),
                Tables\Columns\TextColumn::make('hasil')
                    ->label('Hasil')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'Normal'       => 'success',
                        'Suspek'       => 'warning',
                        'Penyimpangan' => 'danger',
                        default        => 'gray',
                    }),
            ])
            ->defaultSort('tgl_periksa', 'desc')
            ->searchPlaceholder('Cari nama anak...')
            ->filters([
                Tables\Filters\SelectFilter::make('hasil')
                    ->label('Hasil')
                    ->options([
                        'Normal'       => 'Normal',
                        'Suspek'       => 'Suspek',
                        'Penyimpangan' => 'Penyimpangan',
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
                Tables\Actions\EditAction::make()
                    ->label('Edit')
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
            'index'  => Pages\ListSdidtks::route('/'),
            'create' => Pages\CreateSdidtk::route('/create'),
            'edit'   => Pages\EditSdidtk::route('/{record}/edit'),
        ];
    }
}
