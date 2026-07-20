<?php
namespace App\Filament\Kader\Resources;
use App\Filament\Kader\Resources\KehamilanResource\Pages;
use App\Models\Ibu;
use Filament\Forms;

class KehamilanResource extends \App\Filament\Resources\KehamilanResource
{
    protected static ?string $navigationGroup = 'Kesehatan Ibu & Anak';
    protected static ?int    $navigationSort  = 2;

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->whereHas('ibu', fn($q) => $q->where('posyandu_id', auth()->user()->posyandu_id));
    }

    public static function form(\Filament\Forms\Form $form): \Filament\Forms\Form
    {
        $posyanduId = auth()->user()->posyandu_id;
        return parent::form($form)->schema([
            Forms\Components\Section::make('Data Kehamilan')
                ->schema([
                    Forms\Components\Select::make('ibu_id')
                        ->label('Nama Ibu')
                        ->options(\App\Models\Ibu::where('posyandu_id', $posyanduId)->pluck('nama', 'id'))
                        ->searchable()
                        ->required()
                        ->native(false),
                    Forms\Components\DatePicker::make('hpht')
                        ->label('HPHT (Hari Pertama Haid Terakhir)')
                        ->displayFormat('d/m/Y')
                        ->maxDate(now())
                        ->required()
                        ->live()
                        ->afterStateUpdated(function (Forms\Set $set, ?string $state) {
                            if ($state) {
                                $set('tgl_perkiraan_lahir', \Carbon\Carbon::parse($state)->addDays(280)->format('Y-m-d'));
                                $set('usia_kehamilan', \Carbon\Carbon::parse($state)->diffInWeeks(now()));
                            }
                        }),
                    Forms\Components\TextInput::make('usia_kehamilan')
                        ->label('Usia Kehamilan (minggu)')
                        ->numeric()
                        ->suffix('minggu')
                        ->minValue(1)
                        ->nullable(),
                    Forms\Components\DatePicker::make('tgl_perkiraan_lahir')
                        ->label('HPL (Hari Perkiraan Lahir)')
                        ->displayFormat('d/m/Y')
                        ->helperText('Otomatis dihitung dari HPHT + 280 hari'),
                    Forms\Components\Select::make('status')
                        ->label('Status Kehamilan')
                        ->options([
                            'aktif'      => 'Aktif',
                            'melahirkan' => 'Melahirkan',
                            'keguguran'  => 'Keguguran',
                        ])
                        ->default('aktif')
                        ->required()
                        ->native(false),
                    Forms\Components\Textarea::make('catatan')
                        ->label('Catatan')
                        ->rows(3)
                        ->columnSpanFull(),
                ])->columns(2),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListKehamilans::route('/'),
            'create' => Pages\CreateKehamilan::route('/create'),
            'edit'   => Pages\EditKehamilan::route('/{record}/edit'),
        ];
    }
}
