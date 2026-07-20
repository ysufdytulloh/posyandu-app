<?php
namespace App\Filament\Kader\Resources;
use App\Filament\Kader\Resources\PmtDistribusiResource\Pages;
use App\Models\Anak;
use App\Models\Ibu;
use App\Models\Lansia;
use App\Models\JenisPmt;
use Filament\Forms;

class PmtDistribusiResource extends \App\Filament\Resources\PmtDistribusiResource
{
    protected static ?string $navigationGroup = 'PMT';
    protected static ?int    $navigationSort  = 1;

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('posyandu_id', auth()->user()->posyandu_id);
    }

    public static function form(\Filament\Forms\Form $form): \Filament\Forms\Form
    {
        $posyanduId = auth()->user()->posyandu_id;
        return parent::form($form)->schema([
            Forms\Components\Section::make('Data Distribusi PMT')
                ->schema([
                    Forms\Components\Hidden::make('posyandu_id')->default($posyanduId),
                    Forms\Components\Select::make('jenis_pmt_id')
                        ->label('Jenis PMT')
                        ->relationship('jenisPmt', 'nama')
                        ->searchable()->preload()->required(),
                    Forms\Components\Select::make('kader_id')
                        ->label('Kader')
                        ->options(\App\Models\User::where('role', 'kader')->where('posyandu_id', $posyanduId)->pluck('name', 'id'))
                        ->default(auth()->user()->id)
                        ->searchable()->required()->native(false),
                    Forms\Components\DatePicker::make('tgl_distribusi')
                        ->label('Tanggal Distribusi')
                        ->displayFormat('d/m/Y')->maxDate(now())->required()->default(now()),
                    Forms\Components\Select::make('penerima_type')
                        ->label('Jenis Penerima')
                        ->options([
                            'App\Models\Anak'   => 'Balita',
                            'App\Models\Ibu'    => 'Ibu Hamil',
                            'App\Models\Lansia' => 'Lansia',
                        ])
                        ->required()->native(false)->live()
                        ->afterStateUpdated(fn (Forms\Set $set) => $set('penerima_id', null)),
                    Forms\Components\Select::make('penerima_id')
                        ->label('Nama Penerima')
                        ->options(fn (Forms\Get $get) => match($get('penerima_type')) {
                            'App\Models\Anak'   => \App\Models\Anak::where('posyandu_id', $posyanduId)->pluck('nama', 'id'),
                            'App\Models\Ibu'    => \App\Models\Ibu::where('posyandu_id', $posyanduId)->whereHas('kehamilanAktif')->pluck('nama', 'id'),
                            'App\Models\Lansia' => \App\Models\Lansia::where('posyandu_id', $posyanduId)->pluck('nama', 'id'),
                            default             => [],
                        })
                        ->searchable()->required()
                        ->helperText('Pilih jenis penerima terlebih dahulu'),
                    Forms\Components\TextInput::make('jumlah')
                        ->label('Jumlah')->numeric()->minValue(1)->required(),
                    Forms\Components\TextInput::make('satuan')
                        ->label('Satuan')->required()->placeholder('Contoh: bungkus, kg, porsi'),
                    Forms\Components\Textarea::make('catatan')
                        ->label('Catatan')->rows(3)->columnSpanFull(),
                ])->columns(2),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPmtDistribusis::route('/'),
            'create' => Pages\CreatePmtDistribusi::route('/create'),
            'edit'   => Pages\EditPmtDistribusi::route('/{record}/edit'),
        ];
    }
}
