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
    protected static ?string $modelLabel = 'Jenis Imunisasi';
    protected static ?string $pluralModelLabel = 'Data Jenis Imunisasi';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
