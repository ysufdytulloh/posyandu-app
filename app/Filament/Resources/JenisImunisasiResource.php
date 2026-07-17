<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JenisImunisasiResource\Pages;
use App\Filament\Resources\JenisImunisasiResource\RelationManagers;
use App\Models\JenisImunisasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JenisImunisasiResource extends Resource
{
    protected static ?string $navigationGroup = 'Pengaturan';
    protected static ?string $navigationLabel = 'Jenis Imunisasi';
    protected static ?string $modelLabel = 'Data Jenis Imunisasi';
    protected static ?string $pluralModelLabel = 'Jenis Imunisasi';
    protected static ?int $navigationSort = 1;

   public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Section::make('Informasi Imunisasi')
                ->schema([
                    Forms\Components\TextInput::make('nama')
                        ->label('Nama Imunisasi')
                        ->required()
                        ->maxLength(100),
                    Forms\Components\TextInput::make('kode')
                        ->label('Kode')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(20)
                        ->placeholder('Contoh: BCG, DPT1, MR'),
                    Forms\Components\TextInput::make('usia_rekomendasi')
                        ->label('Usia Rekomendasi')
                        ->placeholder('Contoh: 0 bulan, 2 bulan')
                        ->maxLength(50),
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
            Tables\Columns\TextColumn::make('kode')
                ->label('Kode')
                ->badge()
                ->color('primary')
                ->searchable(),
            Tables\Columns\TextColumn::make('nama')
                ->label('Nama Imunisasi')
                ->searchable(),
            Tables\Columns\TextColumn::make('usia_rekomendasi')
                ->label('Usia Rekomendasi')
                ->icon('heroicon-o-clock'),
            Tables\Columns\TextColumn::make('created_at')
                ->label('Ditambahkan')
                ->dateTime('d/m/Y')
        ])
        ->defaultSort('kode')
        ->searchPlaceholder('Cari imunisasi...')
        ->filters([])
        ->actions([
            Tables\Actions\EditAction::make()
                ->label('Edit Data')
                ->icon(null)
                ->button()
                ->color('warning'),
        ])
        ->bulkActions([]);
}

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJenisImunisasis::route('/'),
            'create' => Pages\CreateJenisImunisasi::route('/create'),
            'edit' => Pages\EditJenisImunisasi::route('/{record}/edit'),
        ];
    }
}
