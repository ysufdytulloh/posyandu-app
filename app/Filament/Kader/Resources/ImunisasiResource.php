<?php
namespace App\Filament\Kader\Resources;
use App\Filament\Kader\Resources\ImunisasiResource\Pages;
use App\Models\Anak;
use App\Models\User;
use App\Models\JenisImunisasi;
use Filament\Forms;

class ImunisasiResource extends \App\Filament\Resources\ImunisasiResource
{
    protected static ?string $navigationGroup = 'Kesehatan Ibu & Anak';
    protected static ?int    $navigationSort  = 7;

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->whereHas('anak', fn($q) => $q->where('posyandu_id', auth()->user()->posyandu_id));
    }

    public static function form(\Filament\Forms\Form $form): \Filament\Forms\Form
    {
        $posyanduId = auth()->user()->posyandu_id;
        return parent::form($form)->schema([
            Forms\Components\Section::make('Data Imunisasi')
                ->schema([
                    Forms\Components\Select::make('anak_id')
                        ->label('Nama Anak')
                        ->options(\App\Models\Anak::where('posyandu_id', $posyanduId)->pluck('nama', 'id'))
                        ->searchable()
                        ->required()
                        ->native(false),
                    Forms\Components\Select::make('jenis_imunisasi_id')
                        ->label('Jenis Imunisasi')
                        ->relationship('jenisImunisasi', 'nama')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Forms\Components\Select::make('kader_id')
                        ->label('Kader')
                        ->options(\App\Models\User::where('role', 'kader')->where('posyandu_id', $posyanduId)->pluck('name', 'id'))
                        ->default(auth()->user()->id)
                        ->searchable()
                        ->required()
                        ->native(false),
                    Forms\Components\DatePicker::make('tgl_imunisasi')
                        ->label('Tanggal Imunisasi')
                        ->displayFormat('d/m/Y')
                        ->maxDate(now())
                        ->required()
                        ->default(now()),
                    Forms\Components\Textarea::make('keterangan')
                        ->label('Keterangan')
                        ->rows(3)
                        ->columnSpanFull(),
                ])->columns(2),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListImunisasis::route('/'),
            'create' => Pages\CreateImunisasi::route('/create'),
            'edit'   => Pages\EditImunisasi::route('/{record}/edit'),
        ];
    }
}
