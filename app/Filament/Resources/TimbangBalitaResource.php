<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TimbangBalitaResource\Pages;
use App\Models\TimbangBalita;
use App\Models\Anak;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class TimbangBalitaResource extends Resource
{
    protected static ?string $model            = TimbangBalita::class;
    protected static ?string $navigationLabel  = 'Timbang Balita';
    protected static ?string $modelLabel       = 'Data Timbang Balita';
    protected static ?string $pluralModelLabel = 'Timbang Balita';
    protected static ?string $navigationGroup  = 'Kesehatan Ibu & Anak';
    protected static ?int    $navigationSort   = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Penimbangan')
                    ->schema([
                        Forms\Components\Select::make('posyandu_id')
                            ->label('Posyandu')
                            ->relationship('posyandu', 'nama')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live(),
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
                        Forms\Components\DatePicker::make('tgl_periksa')
                            ->label('Tanggal Periksa')
                            ->displayFormat('d/m/Y')
                            ->maxDate(now())
                            ->required()
                            ->default(now()),
                        Forms\Components\TextInput::make('berat_kg')
                            ->label('Berat Badan (kg)')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->step(0.1)
                            ->suffix('kg')
                            ->required(),
                        Forms\Components\TextInput::make('tinggi_cm')
                            ->label('Tinggi Badan (cm)')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(200)
                            ->step(0.1)
                            ->suffix('cm')
                            ->required(),
                        Forms\Components\TextInput::make('lingkar_kepala_cm')
                            ->label('Lingkar Kepala (cm)')
                            ->numeric()
                            ->step(0.1)
                            ->suffix('cm')
                            ->helperText('Normal: 33-37 cm (newborn) | < 2 th: +1 cm/bulan'),

                        Forms\Components\TextInput::make('lila_cm')
                            ->label('LILA (cm)')
                            ->numeric()
                            ->step(0.1)
                            ->suffix('cm')
                            ->helperText('Normal ≥ 12.5 cm | < 12.5 cm = gizi buruk'),
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
                Tables\Columns\TextColumn::make('posyandu.nama')
                    ->label('Posyandu')
                    ->searchable(),
                Tables\Columns\TextColumn::make('anak.nama')
                    ->label('Nama Anak')
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
                Tables\Columns\TextColumn::make('hasilGizi.status_bbU')
                    ->label('Status BB/U')
                    ->badge()
                    ->color(fn (?string $state): string => match($state) {
                        'Normal'      => 'success',
                        'Gizi Kurang' => 'warning',
                        'Gizi Buruk'  => 'danger',
                        'Gizi Lebih'  => 'info',
                        default       => 'gray',
                    })
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('hasilGizi.status_tbU')
                    ->label('Status TB/U')
                    ->badge()
                    ->color(fn (?string $state): string => match($state) {
                        'Normal'        => 'success',
                        'Pendek'        => 'warning',
                        'Sangat Pendek' => 'danger',
                        'Tinggi'        => 'info',
                        default         => 'gray',
                    })
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('hasilGizi.status_bbTb')
                    ->label('Status BB/TB')
                    ->badge()
                    ->color(fn (?string $state): string => match($state) {
                        'Normal'       => 'success',
                        'Kurus'        => 'warning',
                        'Sangat Kurus' => 'danger',
                        'Gemuk'        => 'info',
                        'Obesitas'     => 'danger',
                        default        => 'gray',
                    })
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('hasilGizi.bbU_zscore')
                    ->label('Z-Score BB/U')
                    ->formatStateUsing(fn (?string $state): string =>
                        $state !== null ? number_format((float)$state, 2) : '-'
                    )
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('hasilGizi.tbU_zscore')
                    ->label('Z-Score TB/U')
                    ->formatStateUsing(fn (?string $state): string =>
                        $state !== null ? number_format((float)$state, 2) : '-'
                    )
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('lingkar_kepala_cm')
                    ->label('LK (cm)')
                    ->numeric(decimalPlaces: 1)
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('lila_cm')
                    ->label('LILA (cm)')
                    ->numeric(decimalPlaces: 1)
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('tgl_periksa', 'desc')
            ->searchPlaceholder('Cari nama anak...')
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
            'index'  => Pages\ListTimbangBalitas::route('/'),
            'create' => Pages\CreateTimbangBalita::route('/create'),
            'edit'   => Pages\EditTimbangBalita::route('/{record}/edit'),
        ];
    }
}
