<?php
namespace App\Filament\Kader\Resources;
use App\Filament\Kader\Resources\TimbangBalitaResource\Pages;
use App\Models\Anak;
use App\Models\User;
use Filament\Forms;

class TimbangBalitaResource extends \App\Filament\Resources\TimbangBalitaResource
{
    protected static ?string $navigationGroup = 'Kesehatan Ibu & Anak';
    protected static ?int    $navigationSort  = 6;

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('posyandu_id', auth()->user()->posyandu_id);
    }

    public static function form(\Filament\Forms\Form $form): \Filament\Forms\Form
    {
        $posyanduId = auth()->user()->posyandu_id;
        return parent::form($form)->schema([
            Forms\Components\Section::make('Data Penimbangan')
                ->schema([
                    Forms\Components\Hidden::make('posyandu_id')
                        ->default($posyanduId),
                    Forms\Components\Select::make('anak_id')
                        ->label('Nama Anak')
                        ->options(\App\Models\Anak::where('posyandu_id', $posyanduId)->pluck('nama', 'id'))
                        ->searchable()
                        ->required()
                        ->live()
                        ->native(false),
                    Forms\Components\Select::make('kader_id')
                        ->label('Kader')
                        ->options(\App\Models\User::where('role', 'kader')->where('posyandu_id', $posyanduId)->pluck('name', 'id'))
                        ->default(auth()->user()->id)
                        ->searchable()
                        ->required()
                        ->native(false),
                    Forms\Components\DatePicker::make('tgl_periksa')
                        ->label('Tanggal Periksa')
                        ->displayFormat('d/m/Y')
                        ->maxDate(now())
                        ->required()
                        ->default(now()),
                    Forms\Components\TextInput::make('berat_kg')
                        ->label('Berat Badan (kg)')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(100)
                        ->step(0.1)
                        ->suffix('kg')
                        ->required(),
                    Forms\Components\TextInput::make('tinggi_cm')
                        ->label('Tinggi Badan (cm)')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(200)
                        ->step(0.1)
                        ->suffix('cm')
                        ->required(),
                    Forms\Components\TextInput::make('lingkar_kepala_cm')
                        ->label('Lingkar Kepala (cm)')
                        ->numeric()
                        ->step(0.1)
                        ->suffix('cm')
                        ->helperText('Normal: 33-37 cm (newborn) | < 2 th: +1 cm/bulan'),
                    Forms\Components\TextInput::make('lila_cm')
                        ->label('LILA (cm)')
                        ->numeric()
                        ->step(0.1)
                        ->suffix('cm')
                        ->helperText('Normal ≥ 12.5 cm | < 12.5 cm = gizi buruk'),
                    Forms\Components\Textarea::make('catatan')
                        ->label('Catatan')
                        ->rows(3)
                        ->columnSpanFull(),
                ])->columns(2),
        ]);
    }

    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return parent::table($table)
            ->columns(
                collect(parent::table($table)->getColumns())
                    ->reject(fn ($col) => $col->getName() === 'posyandu.nama')
                    ->values()
                    ->toArray()
            );
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTimbangBalitas::route('/'),
            'create' => Pages\CreateTimbangBalita::route('/create'),
            'edit'   => Pages\EditTimbangBalita::route('/{record}/edit'),
        ];
    }
}
