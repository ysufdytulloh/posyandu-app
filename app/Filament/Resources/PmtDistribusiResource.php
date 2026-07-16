<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PmtDistribusiResource\Pages;
use App\Filament\Resources\PmtDistribusiResource\RelationManagers;
use App\Models\PmtDistribusi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PmtDistribusiResource extends Resource
{
    protected static ?string $navigationLabel = 'PMT Distribusi';
    protected static ?string $modelLabel = 'PMT Distribusi';
    protected static ?string $pluralModelLabel = 'Data PMT Distribusi';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('jenis_pmt_id')
                    ->relationship('jenisPmt', 'id')
                    ->required(),
                Forms\Components\TextInput::make('penerima_type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('penerima_id')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('posyandu_id')
                    ->relationship('posyandu', 'id')
                    ->required(),
                Forms\Components\Select::make('kader_id')
                    ->relationship('kader', 'name')
                    ->required(),
                Forms\Components\DatePicker::make('tgl_distribusi')
                    ->required(),
                Forms\Components\TextInput::make('jumlah')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('satuan')
                    ->required()
                    ->maxLength(30),
                Forms\Components\Textarea::make('catatan')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('jenisPmt.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('penerima_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('penerima_id')
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
                Tables\Columns\TextColumn::make('jumlah')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('satuan')
                    ->searchable(),
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
            'index' => Pages\ListPmtDistribusis::route('/'),
            'create' => Pages\CreatePmtDistribusi::route('/create'),
            'edit' => Pages\EditPmtDistribusi::route('/{record}/edit'),
        ];
    }
}
