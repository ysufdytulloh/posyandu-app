<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JadwalPosyanduResource\Pages;
use App\Models\JadwalPosyandu;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class JadwalPosyanduResource extends Resource
{
    protected static ?string $model = JadwalPosyandu::class;
    protected static ?string $navigationIcon  = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Jadwal Posyandu';
    protected static ?string $navigationGroup = 'Posyandu';
    protected static ?int    $navigationSort  = 2;
    protected static ?string $modelLabel      = 'Jadwal Posyandu';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Jadwal Posyandu')
                ->schema([
                    Forms\Components\Select::make('posyandu_id')
                        ->label('Posyandu')
                        ->relationship('posyandu', 'nama')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Forms\Components\DatePicker::make('tgl_jadwal')
                        ->label('Tanggal Jadwal')
                        ->displayFormat('d/m/Y')
                        ->minDate(now())
                        ->required(),
                    Forms\Components\TimePicker::make('jam_mulai')
                        ->label('Jam Mulai')
                        ->default('08:00')
                        ->required(),
                    Forms\Components\TimePicker::make('jam_selesai')
                        ->label('Jam Selesai')
                        ->default('11:00'),
                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options([
                            'aktif'   => 'Aktif',
                            'selesai' => 'Selesai',
                            'batal'   => 'Batal',
                        ])
                        ->default('aktif')
                        ->required()
                        ->native(false),
                    Forms\Components\Textarea::make('keterangan')
                        ->label('Keterangan')
                        ->rows(2)
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
                Tables\Columns\TextColumn::make('posyandu.nama')
                    ->label('Posyandu')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tgl_jadwal')
                    ->label('Tanggal')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('jam_mulai')
                    ->label('Jam Mulai'),
                Tables\Columns\TextColumn::make('jam_selesai')
                    ->label('Jam Selesai'),
                Tables\Columns\TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->limit(30),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'aktif',
                        'gray'    => 'selesai',
                        'danger'  => 'batal',
                    ]),
            ])
            ->defaultSort('tgl_jadwal', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('posyandu_id')
                    ->label('Posyandu')
                    ->relationship('posyandu', 'nama'),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'aktif'   => 'Aktif',
                        'selesai' => 'Selesai',
                        'batal'   => 'Batal',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListJadwalPosyandus::route('/'),
            'create' => Pages\CreateJadwalPosyandu::route('/create'),
            'edit'   => Pages\EditJadwalPosyandu::route('/{record}/edit'),
        ];
    }
}
