<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PmtDistribusiResource\Pages;
use App\Models\PmtDistribusi;
use App\Models\Anak;
use App\Models\Ibu;
use App\Models\Lansia;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PmtDistribusiResource extends Resource
{
    protected static ?string $model            = PmtDistribusi::class;
    protected static ?string $navigationLabel  = 'PMT Distribusi';
    protected static ?string $modelLabel       = 'Data PMT Distribusi';
    protected static ?string $pluralModelLabel = 'Data PMT Distribusi';
    protected static ?string $navigationGroup  = 'Transaksi';
    protected static ?int    $navigationSort   = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Distribusi PMT')
                    ->schema([
                        Forms\Components\Select::make('posyandu_id')
                            ->label('Posyandu')
                            ->relationship('posyandu', 'nama')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Forms\Set $set) {
                                $set('penerima_type', null);
                                $set('penerima_id', null);
                                $set('kader_id', null);
                            }),
                        Forms\Components\Select::make('jenis_pmt_id')
                            ->label('Jenis PMT')
                            ->relationship('jenisPmt', 'nama')
                            ->searchable()
                            ->preload()
                            ->required(),
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
                        Forms\Components\DatePicker::make('tgl_distribusi')
                            ->label('Tanggal Distribusi')
                            ->displayFormat('d/m/Y')
                            ->maxDate(now())
                            ->required()
                            ->default(now()),
                        Forms\Components\Select::make('penerima_type')
                            ->label('Jenis Penerima')
                            ->options([
                                'App\Models\Anak'   => 'Balita',
                                'App\Models\Ibu'    => 'Ibu Hamil',
                                'App\Models\Lansia' => 'Lansia',
                            ])
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn (Forms\Set $set) =>
                                $set('penerima_id', null)
                            ),
                        Forms\Components\Select::make('penerima_id')
                            ->label('Nama Penerima')
                            ->options(function (Forms\Get $get) {
                                $type      = $get('penerima_type');
                                $posyandId = $get('posyandu_id');

                                return match($type) {
                                    'App\Models\Anak'   => Anak::where('posyandu_id', $posyandId)->pluck('nama', 'id'),
                                    'App\Models\Ibu'    => Ibu::where('posyandu_id', $posyandId)->pluck('nama', 'id'),
                                    'App\Models\Lansia' => Lansia::where('posyandu_id', $posyandId)->pluck('nama', 'id'),
                                    default             => [],
                                };
                            })
                            ->searchable()
                            ->required()
                            ->helperText('Pilih jenis penerima terlebih dahulu'),
                        Forms\Components\TextInput::make('jumlah')
                            ->label('Jumlah')
                            ->numeric()
                            ->minValue(1)
                            ->required(),
                        Forms\Components\TextInput::make('satuan')
                            ->label('Satuan')
                            ->required()
                            ->placeholder('Contoh: bungkus, kg, porsi'),
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
                Tables\Columns\TextColumn::make('posyandu.nama')
                    ->label('Posyandu')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenisPmt.nama')
                    ->label('Jenis PMT')
                    ->searchable()
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('penerima_type')
                    ->label('Jenis Penerima')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'App\Models\Anak'   => 'Balita',
                        'App\Models\Ibu'    => 'Ibu Hamil',
                        'App\Models\Lansia' => 'Lansia',
                        default             => $state,
                    })
                    ->color(fn (string $state): string => match($state) {
                        'App\Models\Anak'   => 'info',
                        'App\Models\Ibu'    => 'warning',
                        'App\Models\Lansia' => 'danger',
                        default             => 'gray',
                    }),
                Tables\Columns\TextColumn::make('tgl_distribusi')
                    ->label('Tgl Distribusi')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('jumlah')
                    ->label('Jumlah')
                    ->formatStateUsing(fn ($record) => $record->jumlah . ' ' . $record->satuan),
                Tables\Columns\TextColumn::make('kader.name')
                    ->label('Kader'),
            ])
            ->defaultSort('tgl_distribusi', 'desc')
            ->searchPlaceholder('Cari data PMT...')
            ->filters([
                Tables\Filters\SelectFilter::make('posyandu_id')
                    ->label('Posyandu')
                    ->relationship('posyandu', 'nama'),
                Tables\Filters\SelectFilter::make('jenis_pmt_id')
                    ->label('Jenis PMT')
                    ->relationship('jenisPmt', 'nama'),
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
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPmtDistribusis::route('/'),
            'create' => Pages\CreatePmtDistribusi::route('/create'),
            'edit'   => Pages\EditPmtDistribusi::route('/{record}/edit'),
        ];
    }
}
