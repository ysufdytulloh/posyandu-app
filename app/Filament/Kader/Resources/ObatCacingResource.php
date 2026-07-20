<?php
namespace App\Filament\Kader\Resources;
use App\Filament\Kader\Resources\ObatCacingResource\Pages;
use App\Models\Anak;
use App\Models\User;
use Filament\Forms;

class ObatCacingResource extends \App\Filament\Resources\ObatCacingResource
{
    protected static ?string $navigationGroup = 'Kesehatan Ibu & Anak';
    protected static ?int    $navigationSort  = 10;

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->whereHas('anak', fn($q) => $q->where('posyandu_id', auth()->user()->posyandu_id));
    }

    public static function form(\Filament\Forms\Form $form): \Filament\Forms\Form
    {
        $posyanduId = auth()->user()->posyandu_id;
        return parent::form($form)->schema([
            Forms\Components\Section::make('Data Pemberian Obat Cacing')
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
                    Forms\Components\DatePicker::make('tgl_pemberian')
                        ->label('Tanggal Pemberian')
                        ->displayFormat('d/m/Y')
                        ->maxDate(now())
                        ->required()
                        ->default(now()),
                    Forms\Components\Select::make('dosis')
                        ->label('Dosis')
                        ->options([
                            '500mg' => '500mg (Albendazole) — usia 1-5 tahun',
                        ])
                        ->default('500mg')
                        ->required()
                        ->native(false),
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
            'index'  => Pages\ListObatCacings::route('/'),
            'create' => Pages\CreateObatCacing::route('/create'),
            'edit'   => Pages\EditObatCacing::route('/{record}/edit'),
        ];
    }
}
