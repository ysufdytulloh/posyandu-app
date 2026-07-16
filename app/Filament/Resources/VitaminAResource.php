<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VitaminAResource\Pages;
use App\Models\VitaminA;
use App\Models\Anak;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VitaminAResource extends Resource
{
    protected static ?string $model            = VitaminA::class;
    protected static ?string $navigationLabel  = 'Vitamin A';
    protected static ?string $modelLabel       = 'Data Vitamin A';
    protected static ?string $pluralModelLabel = 'Data Vitamin A';
    protected static ?string $navigationGroup  = 'Transaksi';
    protected static ?int    $navigationSort   = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Distribusi Vitamin A')
                    ->schema([
                        Forms\Components\Select::make('posyandu_id')
                            ->label('Posyandu')
                            ->relationship('posyandu', 'nama')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Forms\Set $set) {
                                $set('anak_id', null);
                                $set('kader_id', null);
                            }),
                        Forms\Components\Select::make('anak_id')
                            ->label('Nama Anak')
                            ->options(fn (Forms\Get $get) =>
                                Anak::where('posyandu_id', $get('posyandu_id'))
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
                        Forms\Components\DatePicker::make('tgl_distribusi')
                            ->label('Tanggal Distribusi')
                            ->displayFormat('d/m/Y')
                            ->maxDate(now())
                            ->required()
                            ->default(now()),
                        Forms\Components\Select::make('dosis')
                            ->label('Dosis')
                            ->options([
                                'Biru (100.000 IU)'  => 'Biru (100.000 IU) — 6-11 bulan',
                                'Merah (200.000 IU)' => 'Merah (200.000 IU) — 12-59 bulan',
                            ])
                            ->required()
                            ->helperText('Biru untuk usia 6-11 bulan, Merah untuk 12-59 bulan'),
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
                Tables\Columns\TextColumn::make('anak.nama')
                    ->label('Nama Anak')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tgl_distribusi')
                    ->label('Tgl Distribusi')
                    ->date('d/m/Y'),
                Tables\Columns\TextColumn::make('dosis')
                    ->label('Dosis')
                    ->badge()
                    ->color(fn (string $state): string =>
                        str_contains($state, 'Biru') ? 'info' : 'danger'
                    ),
                Tables\Columns\TextColumn::make('kader.name')
                    ->label('Kader'),
            ])
            ->defaultSort('tgl_distribusi', 'desc')
            ->searchPlaceholder('Cari nama anak...')
            ->filters([
                Tables\Filters\SelectFilter::make('posyandu_id')
                    ->label('Posyandu')
                    ->relationship('posyandu', 'nama'),
                Tables\Filters\SelectFilter::make('dosis')
                    ->label('Dosis')
                    ->options([
                        'Biru (100.000 IU)'  => 'Biru (100.000 IU)',
                        'Merah (200.000 IU)' => 'Merah (200.000 IU)',
                    ]),
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
            'index'  => Pages\ListVitaminAS::route('/'),
            'create' => Pages\CreateVitaminA::route('/create'),
            'edit'   => Pages\EditVitaminA::route('/{record}/edit'),
        ];
    }
}
