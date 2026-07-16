<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JenisPmtResource\Pages;
use App\Filament\Resources\JenisPmtResource\RelationManagers;
use App\Models\JenisPmt;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JenisPmtResource extends Resource
{
    protected static ?string $navigationGroup = 'Pengaturan';
    protected static ?string $navigationLabel = 'Jenis PMT';
    protected static ?string $modelLabel = 'Jenis PMT';
    protected static ?string $pluralModelLabel = 'Data Jenis PMT';
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
            'index' => Pages\ListJenisPmts::route('/'),
            'create' => Pages\CreateJenisPmt::route('/create'),
            'edit' => Pages\EditJenisPmt::route('/{record}/edit'),
        ];
    }
}
