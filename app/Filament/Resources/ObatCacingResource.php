<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ObatCacingResource\Pages;
use App\Models\ObatCacing;
use App\Models\Anak;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ObatCacingResource extends Resource
{
    protected static ?string $model            = ObatCacing::class;
    protected static ?string $navigationLabel  = 'Obat Cacing';
    protected static ?string $modelLabel       = 'Data Obat Cacing';
    protected static ?string $pluralModelLabel = 'Data Obat Cacing';
    protected static ?string $navigationGroup  = 'Kesehatan Ibu & Anak';
    protected static ?int    $navigationSort   = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Pemberian Obat Cacing')
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
                            ->options(
                                \App\Models\User::where('role', 'kader')->pluck('name', 'id')
                            )
                            ->searchable()
                            ->required(),
                        Forms\Components\DatePicker::make('tgl_pemberian')
                            ->label('Tanggal Pemberian')
                            ->displayFormat('d/m/Y')
                            ->maxDate(now())
                            ->required()
                            ->default(now()),
                        Forms\Components\Select::make('dosis')
                            ->label('Dosis')
                            ->options([
                                '500mg' => '500mg (Albendazole) — usia 1-5 tahun',
                            ])
                            ->default('500mg')
                            ->required()
                            ->native(false),
                        Forms\Components\Textarea::make('keterangan')
                            ->label('Keterangan')
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
                Tables\Columns\TextColumn::make('tgl_pemberian')
                    ->label('Tgl Pemberian')
                    ->date('d/m/Y'),
                Tables\Columns\TextColumn::make('dosis')
                    ->label('Dosis')
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('kader.name')
                    ->label('Kader'),
            ])
            ->defaultSort('tgl_pemberian', 'desc')
            ->searchPlaceholder('Cari nama anak...')
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
            'index'  => Pages\ListObatCacings::route('/'),
            'create' => Pages\CreateObatCacing::route('/create'),
            'edit'   => Pages\EditObatCacing::route('/{record}/edit'),
        ];
    }
}
