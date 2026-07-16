<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PeriksaLansiaResource\Pages;
use App\Filament\Resources\PeriksaLansiaResource\RelationManagers;
use App\Models\PeriksaLansia;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PeriksaLansiaResource extends Resource
{
    protected static ?string $navigationLabel = 'Pemeriksaan Lansia';
    protected static ?string $modelLabel = 'Pemeriksaan Lansia';
    protected static ?string $pluralModelLabel = 'Data Pemeriksaan Lansia';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('lansia_id')
                    ->relationship('lansia', 'id')
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
                Forms\Components\TextInput::make('imt')
                    ->numeric(),
                Forms\Components\TextInput::make('tensi_sistol')
                    ->numeric(),
                Forms\Components\TextInput::make('tensi_diastol')
                    ->numeric(),
                Forms\Components\TextInput::make('gula_darah')
                    ->numeric(),
                Forms\Components\TextInput::make('kolesterol')
                    ->numeric(),
                Forms\Components\TextInput::make('asam_urat')
                    ->numeric(),
                Forms\Components\TextInput::make('lingkar_perut')
                    ->numeric(),
                Forms\Components\Textarea::make('keluhan')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('lansia.id')
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
                Tables\Columns\TextColumn::make('imt')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tensi_sistol')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tensi_diastol')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gula_darah')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kolesterol')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('asam_urat')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lingkar_perut')
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
            'index' => Pages\ListPeriksaLansias::route('/'),
            'create' => Pages\CreatePeriksaLansia::route('/create'),
            'edit' => Pages\EditPeriksaLansia::route('/{record}/edit'),
        ];
    }
}
