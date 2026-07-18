<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IbuResource\Pages;
use App\Models\Ibu;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class IbuResource extends Resource
{
    protected static ?string $model            = Ibu::class;
    protected static ?string $navigationGroup  = 'Kesehatan Ibu & Anak';
    protected static ?string $navigationLabel  = 'Data Ibu';
    protected static ?string $modelLabel       = 'Data Ibu';
    protected static ?string $pluralModelLabel = 'Data Ibu';
    protected static ?int    $navigationSort   = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Ibu')
                    ->schema([
                        Forms\Components\Select::make('posyandu_id')
                            ->label('Posyandu')
                            ->relationship('posyandu', 'nama')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\TextInput::make('nik')
                            ->label('NIK')
                            ->inputMode('numeric')
                            ->minLength(16)
                            ->maxLength(16)
                            ->unique(ignoreRecord: true)
                            ->placeholder('16 digit NIK')
                            ->rule('digits:16'),

                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Nama lengkap ibu')
                            ->dehydrateStateUsing(fn (string $state): string => strtoupper($state))
                            ->extraInputAttributes(['style' => 'text-transform: uppercase']),

                        Forms\Components\DatePicker::make('tgl_lahir')
                            ->label('Tanggal Lahir')
                            ->displayFormat('d/m/Y')
                            ->maxDate(now()),

                        Forms\Components\TextInput::make('no_hp')
                            ->label('No. HP')
                            ->tel()
                            ->prefix('+62')
                            ->placeholder('81234567890')
                            ->maxLength(13)
                            ->dehydrateStateUsing(fn (?string $state): ?string =>
                                $state ? '+62' . ltrim($state, '0') : null
                            )
                            ->formatStateUsing(fn (?string $state): ?string =>
                                $state ? ltrim(str_replace('+62', '', $state), '0') : null
                            ),

                        Forms\Components\Select::make('goldar')
                            ->label('Golongan Darah')
                            ->options([
                                'A'          => 'A',
                                'B'          => 'B',
                                'AB'         => 'AB',
                                'O'          => 'O',
                                'Tidak Tahu' => 'Tidak Tahu',
                            ]),
                    ])->columns(2),

                Forms\Components\Section::make('Alamat')
                    ->schema([
                        Forms\Components\Textarea::make('alamat')
                            ->label('Alamat Lengkap')
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
                Tables\Columns\TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Lengkap')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tgl_lahir')
                    ->label('Tgl Lahir')
                    ->date('d/m/Y'),
                Tables\Columns\TextColumn::make('no_hp')
                    ->label('No. HP'),
                Tables\Columns\TextColumn::make('goldar')
                    ->label('Gol. Darah')
                    ->badge()
                    ->color('info'),
            ])
            ->defaultSort('nama')
            ->searchPlaceholder('Cari nama atau NIK...')
            ->filters([
                Tables\Filters\SelectFilter::make('posyandu_id')
                    ->label('Posyandu')
                    ->relationship('posyandu', 'nama'),
                Tables\Filters\SelectFilter::make('goldar')
                    ->label('Golongan Darah')
                    ->options([
                        'A'  => 'A',
                        'B'  => 'B',
                        'AB' => 'AB',
                        'O'  => 'O',
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
            ->bulkActions([])
            ->paginated(false);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['nama', 'nik'];
    }

    public static function getGlobalSearchResultTitle(\Illuminate\Database\Eloquent\Model $record): string
    {
        return $record->nama;
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        return [
            'Posyandu' => $record->posyandu?->nama ?? '-',
            'NIK'      => $record->nik ?? '-',
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListIbus::route('/'),
            'create' => Pages\CreateIbu::route('/create'),
            'edit'   => Pages\EditIbu::route('/{record}/edit'),
        ];
    }
}
