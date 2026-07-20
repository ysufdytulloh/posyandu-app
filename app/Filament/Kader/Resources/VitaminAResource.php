<?php
namespace App\Filament\Kader\Resources;
use App\Filament\Kader\Resources\VitaminAResource\Pages;
use App\Models\Anak;
use App\Models\User;
use Filament\Forms;

class VitaminAResource extends \App\Filament\Resources\VitaminAResource
{
    protected static ?string $navigationGroup = 'Kesehatan Ibu & Anak';
    protected static ?int    $navigationSort  = 8;

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('posyandu_id', auth()->user()->posyandu_id);
    }

   public static function form(\Filament\Forms\Form $form): \Filament\Forms\Form
    {
        $posyanduId = auth()->user()->posyandu_id;
        return parent::form($form)->schema([
            Forms\Components\Section::make('Data Distribusi Vitamin A')
                ->schema([
                    Forms\Components\Hidden::make('posyandu_id')
                        ->default($posyanduId),
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
                    Forms\Components\DatePicker::make('tgl_distribusi')
                        ->label('Tanggal Distribusi')
                        ->displayFormat('d/m/Y')
                        ->maxDate(now())
                        ->required()
                        ->default(now()),
                    Forms\Components\Select::make('dosis')
                        ->label('Dosis')
                        ->options([
                            'Biru (100.000 IU)'  => 'Biru (100.000 IU) — 6-11 bulan',
                            'Merah (200.000 IU)' => 'Merah (200.000 IU) — 12-59 bulan',
                        ])
                        ->required()
                        ->helperText('Biru untuk usia 6-11 bulan, Merah untuk 12-59 bulan')
                        ->native(false),
                ])->columns(2),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListVitaminAs::route('/'),
            'create' => Pages\CreateVitaminA::route('/create'),
            'edit'   => Pages\EditVitaminA::route('/{record}/edit'),
        ];
    }
}
