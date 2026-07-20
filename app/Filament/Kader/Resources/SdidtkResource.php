<?php
namespace App\Filament\Kader\Resources;
use App\Filament\Kader\Resources\SdidtkResource\Pages;
use App\Models\Anak;
use App\Models\User;
use Filament\Forms;

class SdidtkResource extends \App\Filament\Resources\SdidtkResource
{
    protected static ?string $navigationGroup = 'Kesehatan Ibu & Anak';
    protected static ?int    $navigationSort  = 9;

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->whereHas('anak', fn($q) => $q->where('posyandu_id', auth()->user()->posyandu_id));
    }

    public static function form(\Filament\Forms\Form $form): \Filament\Forms\Form
    {
        $posyanduId = auth()->user()->posyandu_id;
        return parent::form($form)->schema([
            Forms\Components\Section::make('Data Pemeriksaan')
                ->schema([
                    Forms\Components\Select::make('anak_id')
                        ->label('Nama Anak')
                        ->options(\App\Models\Anak::where('posyandu_id', $posyanduId)->pluck('nama', 'id'))
                        ->searchable()
                        ->required()
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
                    Forms\Components\TextInput::make('usia_bulan')
                        ->label('Usia (bulan)')
                        ->numeric()
                        ->suffix('bulan')
                        ->required(),
                ])->columns(2),

            Forms\Components\Section::make('Penilaian SDIDTK')
                ->description('S = Sesuai | M = Meragukan | P = Penyimpangan')
                ->schema([
                    Forms\Components\Select::make('motorik_kasar')
                        ->label('Motorik Kasar')
                        ->options(['S'=>'S — Sesuai','M'=>'M — Meragukan','P'=>'P — Penyimpangan'])
                        ->required()->native(false),
                    Forms\Components\Select::make('motorik_halus')
                        ->label('Motorik Halus')
                        ->options(['S'=>'S — Sesuai','M'=>'M — Meragukan','P'=>'P — Penyimpangan'])
                        ->required()->native(false),
                    Forms\Components\Select::make('bicara_bahasa')
                        ->label('Bicara & Bahasa')
                        ->options(['S'=>'S — Sesuai','M'=>'M — Meragukan','P'=>'P — Penyimpangan'])
                        ->required()->native(false),
                    Forms\Components\Select::make('sosial_kemandirian')
                        ->label('Sosial & Kemandirian')
                        ->options(['S'=>'S — Sesuai','M'=>'M — Meragukan','P'=>'P — Penyimpangan'])
                        ->required()->native(false),
                    Forms\Components\Select::make('hasil')
                        ->label('Hasil Keseluruhan')
                        ->options([
                            'Normal'       => 'Normal — Semua sesuai',
                            'Suspek'       => 'Suspek — Ada yang meragukan',
                            'Penyimpangan' => 'Penyimpangan — Ada penyimpangan',
                        ])
                        ->required()->native(false),
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
            'index'  => Pages\ListSdidtks::route('/'),
            'create' => Pages\CreateSdidtk::route('/create'),
            'edit'   => Pages\EditSdidtk::route('/{record}/edit'),
        ];
    }
}
