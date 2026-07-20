<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KehamilanResource\Pages;
use App\Models\Ibu;
use App\Models\Kehamilan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class KehamilanResource extends Resource
{
    protected static ?string $model            = Kehamilan::class;
    protected static ?string $navigationLabel  = 'Data Ibu Hamil';
    protected static ?string $modelLabel       = 'Data Ibu Hamil';
    protected static ?string $pluralModelLabel = 'Data Ibu Hamil';
    protected static ?string $navigationGroup  = 'Kesehatan Ibu & Anak';
    protected static ?int    $navigationSort   = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Kehamilan')
                    ->schema([
                        Forms\Components\Select::make('ibu_id')
                            ->label('Nama Ibu')
                            ->relationship('ibu', 'nama')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\DatePicker::make('hpht')
                            ->label('HPHT (Hari Pertama Haid Terakhir)')
                            ->displayFormat('d/m/Y')
                            ->maxDate(now())
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Forms\Set $set, ?string $state) {
                                if ($state) {
                                    $hpl = \Carbon\Carbon::parse($state)->addDays(280)->format('Y-m-d');
                                    $set('tgl_perkiraan_lahir', $hpl);
                                    $usia = \Carbon\Carbon::parse($state)->diffInWeeks(now());
                                    $set('usia_kehamilan', $usia);
                                }
                            }),
                        Forms\Components\TextInput::make('usia_kehamilan')
                            ->label('Usia Kehamilan (minggu)')
                            ->numeric()
                            ->suffix('minggu')
                            ->minValue(1)
                            ->nullable(),
                        Forms\Components\DatePicker::make('tgl_perkiraan_lahir')
                            ->label('HPL (Hari Perkiraan Lahir)')
                            ->displayFormat('d/m/Y')
                            ->helperText('Otomatis dihitung dari HPHT + 280 hari'),
                        Forms\Components\Select::make('status')
                            ->label('Status Kehamilan')
                            ->options([
                                'aktif'       => 'Aktif',
                                'melahirkan'  => 'Melahirkan',
                                'keguguran'   => 'Keguguran',
                            ])
                            ->default('aktif')
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
                Tables\Columns\TextColumn::make('ibu.nama')
                    ->label('Nama Ibu')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ibu.posyandu.nama')
                    ->label('Posyandu')
                    ->searchable(),
                Tables\Columns\TextColumn::make('hpht')
                    ->label('HPHT')
                    ->date('d/m/Y'),
                Tables\Columns\TextColumn::make('usia_kehamilan')
                    ->label('Usia')
                    ->formatStateUsing(fn ($state) => $state . ' minggu'),
                Tables\Columns\TextColumn::make('tgl_perkiraan_lahir')
                    ->label('HPL')
                    ->date('d/m/Y'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'aktif'      => 'Aktif',
                        'melahirkan' => 'Melahirkan',
                        'keguguran'  => 'Keguguran',
                        default      => $state,
                    })
                    ->color(fn (string $state): string => match($state) {
                        'aktif'      => 'success',
                        'melahirkan' => 'info',
                        'keguguran'  => 'danger',
                        default      => 'gray',
                    }),
            ])
            ->defaultSort('hpht', 'desc')
            ->searchPlaceholder('Cari nama ibu...')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'aktif'      => 'Aktif',
                        'melahirkan' => 'Melahirkan',
                        'keguguran'  => 'Keguguran',
                    ])
                    ->native(false),
                Tables\Filters\SelectFilter::make('ibu.posyandu_id')
                    ->label('Posyandu')
                    ->relationship('ibu.posyandu', 'nama')
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
            'index'  => Pages\ListKehamilans::route('/'),
            'create' => Pages\CreateKehamilan::route('/create'),
            'edit'   => Pages\EditKehamilan::route('/{record}/edit'),
        ];
    }
}
