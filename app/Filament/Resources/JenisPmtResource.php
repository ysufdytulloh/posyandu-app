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
    protected static ?string $modelLabel = 'Data Jenis PMT';
    protected static ?string $pluralModelLabel = 'Jenis PMT';
    protected static ?int $navigationSort = 2;
    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Section::make('Informasi PMT')
                ->schema([
                    Forms\Components\TextInput::make('nama')
                        ->label('Nama PMT')
                        ->required()
                        ->maxLength(100),
                    Forms\Components\TextInput::make('satuan')
                        ->label('Satuan')
                        ->required()
                        ->placeholder('Contoh: bungkus, kaleng, kg')
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
                Tables\Columns\TextColumn::make('no')
                    ->label('No')
                    ->rowIndex(),
            Tables\Columns\TextColumn::make('nama')
                ->label('Nama PMT')
                ->searchable(),
            Tables\Columns\TextColumn::make('satuan')
                ->label('Satuan')
                ->badge()
                ->color('success'),
        ])
        ->defaultSort('nama')
        ->searchPlaceholder('Cari PMT...')
        ->filters([])
        ->actions([
            Tables\Actions\EditAction::make()
                ->label('Edit Data')
                ->button()
                ->color('warning')
                ->icon(null),
        ])
        ->bulkActions([])
            ->paginated(false);
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
