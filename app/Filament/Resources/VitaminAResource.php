<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VitaminAResource\Pages;
use App\Filament\Resources\VitaminAResource\RelationManagers;
use App\Models\VitaminA;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VitaminAResource extends Resource
{
    protected static ?string $navigationLabel = 'Vitamin A';
    protected static ?string $modelLabel = 'Vitamin A';
    protected static ?string $pluralModelLabel = 'Data Vitamin A';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('anak_id')
                    ->relationship('anak', 'id')
                    ->required(),
                Forms\Components\Select::make('posyandu_id')
                    ->relationship('posyandu', 'id')
                    ->required(),
                Forms\Components\Select::make('kader_id')
                    ->relationship('kader', 'name')
                    ->required(),
                Forms\Components\DatePicker::make('tgl_distribusi')
                    ->required(),
                Forms\Components\TextInput::make('dosis')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('anak.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('posyandu.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kader.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tgl_distribusi')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dosis'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListVitaminAS::route('/'),
            'create' => Pages\CreateVitaminA::route('/create'),
            'edit' => Pages\EditVitaminA::route('/{record}/edit'),
        ];
    }
}
