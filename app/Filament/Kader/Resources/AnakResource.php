<?php
namespace App\Filament\Kader\Resources;
use App\Filament\Kader\Resources\AnakResource\Pages;
use App\Models\Ibu;
use Filament\Forms;

class AnakResource extends \App\Filament\Resources\AnakResource
{
    protected static ?string $navigationGroup = 'Kesehatan Ibu & Anak';
    protected static ?int    $navigationSort  = 5;

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('posyandu_id', auth()->user()->posyandu_id);
    }

    public static function form(\Filament\Forms\Form $form): \Filament\Forms\Form
    {
        $posyanduId = auth()->user()->posyandu_id;
        return parent::form($form)->schema([
            Forms\Components\Section::make('Informasi Anak')
                ->schema([
                    Forms\Components\Select::make('ibu_id')
                        ->label('Nama Ibu')
                        ->options(\App\Models\Ibu::where('posyandu_id', $posyanduId)->pluck('nama', 'id'))
                        ->searchable()
                        ->required()
                        ->native(false),
                    Forms\Components\Hidden::make('posyandu_id')
                        ->default($posyanduId),
                    Forms\Components\TextInput::make('nama')
                        ->label('Nama Lengkap')
                        ->required()
                        ->maxLength(255)
                        ->dehydrateStateUsing(fn (string $state): string => strtoupper($state))
                        ->extraInputAttributes(['style' => 'text-transform: uppercase']),
                    Forms\Components\TextInput::make('nik')
                        ->label('NIK')
                        ->inputMode('numeric')
                        ->minLength(16)
                        ->maxLength(16)
                        ->unique(ignoreRecord: true)
                        ->placeholder('16 digit NIK'),

                    Forms\Components\Select::make('jk')
                        ->label('Jenis Kelamin')
                        ->options(['L'=>'Laki-laki','P'=>'Perempuan'])
                        ->required()
                        ->native(false),
                    Forms\Components\DatePicker::make('tgl_lahir')
                        ->label('Tanggal Lahir')
                        ->displayFormat('d/m/Y')
                        ->maxDate(now())
                        ->required(),
                    Forms\Components\TextInput::make('anak_ke')
                        ->label('Anak Ke')
                        ->numeric()
                        ->minValue(1)
                        ->default(1)
                        ->required(),
                ])->columns(2),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAnaks::route('/'),
            'create' => Pages\CreateAnak::route('/create'),
            'edit'   => Pages\EditAnak::route('/{record}/edit'),
        ];
    }
}
