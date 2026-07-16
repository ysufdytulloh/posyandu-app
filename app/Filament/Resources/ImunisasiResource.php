<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ImunisasiResource\Pages;
use App\Filament\Resources\ImunisasiResource\RelationManagers;
use App\Models\Imunisasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ImunisasiResource extends Resource
{
    protected static ?string $navigationLabel = 'Imunisasi';
    protected static ?string $modelLabel = 'Imunisasi';
    protected static ?string $pluralModelLabel = 'Data Imunisasi';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?int $navigationSort = 2;

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
            'index' => Pages\ListImunisasis::route('/'),
            'create' => Pages\CreateImunisasi::route('/create'),
            'edit' => Pages\EditImunisasi::route('/{record}/edit'),
        ];
    }
}
