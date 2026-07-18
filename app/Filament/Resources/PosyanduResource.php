<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PosyanduResource\Pages;
use App\Models\Posyandu;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;

class PosyanduResource extends Resource
{
    protected static ?string $model = Posyandu::class;
    protected static ?string $navigationLabel   = 'Data Posyandu';
    protected static ?string $modelLabel        = 'Data Posyandu';
    protected static ?string $pluralModelLabel  = 'Data Posyandu';
    protected static ?string $navigationGroup   = 'Posyandu';
    protected static ?int    $navigationSort    = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Posyandu')
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Posyandu')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('alamat')
                            ->label('Alamat')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('kecamatan')
                            ->label('Kecamatan')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('kelurahan')
                            ->label('Kelurahan')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('rt')
                            ->label('RT')
                            ->maxLength(5),
                        Forms\Components\TextInput::make('rw')
                            ->label('RW')
                            ->maxLength(5),
                    ])->columns(2),

                Forms\Components\Section::make('Pengaturan')
                    ->schema([
                        Forms\Components\FileUpload::make('logo')
                            ->label('Logo Posyandu')
                            ->image()
                            ->directory('logo-posyandu')
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
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
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Posyandu')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kader')
                    ->label('Kader')
                    ->getStateUsing(fn ($record) =>
                        \App\Models\User::where('posyandu_id', $record->id)
                            ->where('role', 'kader')
                            ->pluck('name')
                            ->join(', ')
                    )
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('kelurahan')
                    ->label('Kelurahan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kecamatan')
                    ->label('Kecamatan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rt')
                    ->label('RT'),
                Tables\Columns\TextColumn::make('rw')
                    ->label('RW'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y')
                    ->hidden(),
                Tables\Columns\TextColumn::make('is_active')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Aktif' : 'Tidak Aktif')
                    ->color(fn (bool $state): string => $state ? 'success' : 'danger'),
            ])
            ->defaultSort('nama')
            ->searchPlaceholder('Cari posyandu...')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->trueLabel('Aktif')
                    ->falseLabel('Tidak Aktif'),
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
        return ['nama', 'kelurahan', 'kecamatan'];
    }

    public static function getGlobalSearchResultTitle(\Illuminate\Database\Eloquent\Model $record): string
    {
        return $record->nama;
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        return [
            'Kelurahan' => $record->kelurahan ?? '-',
            'Kecamatan' => $record->kecamatan ?? '-',
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPosyandus::route('/'),
            'create' => Pages\CreatePosyandu::route('/create'),
            'edit'   => Pages\EditPosyandu::route('/{record}/edit'),
        ];
    }
}
