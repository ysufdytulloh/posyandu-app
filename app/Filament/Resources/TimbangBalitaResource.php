<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TimbangBalitaResource\Pages;
use App\Filament\Resources\TimbangBalitaResource\RelationManagers;
use App\Models\TimbangBalita;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TimbangBalitaResource extends Resource
{
    protected static ?string $navigationLabel = 'Timbang Balita';
    protected static ?string $modelLabel = 'Timbang Balita';
    protected static ?string $pluralModelLabel = 'Data Timbang Balita';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?int $navigationSort = 1;

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
                Forms\Components\DatePicker::make('tgl_periksa')
                    ->required(),
                Forms\Components\TextInput::make('berat_kg')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('tinggi_cm')
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('catatan')
                    ->columnSpanFull(),
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
                Tables\Columns\TextColumn::make('tgl_periksa')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('berat_kg')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tinggi_cm')
                    ->numeric()
                    ->sortable(),
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
            'index' => Pages\ListTimbangBalitas::route('/'),
            'create' => Pages\CreateTimbangBalita::route('/create'),
            'edit' => Pages\EditTimbangBalita::route('/{record}/edit'),
        ];
    }
}
