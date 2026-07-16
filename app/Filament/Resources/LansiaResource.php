<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LansiaResource\Pages;
use App\Filament\Resources\LansiaResource\RelationManagers;
use App\Models\Lansia;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LansiaResource extends Resource
{
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Data Lansia';
    protected static ?string $modelLabel = 'Lansia';
    protected static ?string $pluralModelLabel = 'Data Lansia';
    protected static ?int $navigationSort = 4;

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
            'index' => Pages\ListLansias::route('/'),
            'create' => Pages\CreateLansia::route('/create'),
            'edit' => Pages\EditLansia::route('/{record}/edit'),
        ];
    }
}
