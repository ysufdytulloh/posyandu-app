<?php
namespace App\Filament\Kader\Resources;
use App\Filament\Kader\Resources\LansiaResource\Pages;
use Filament\Forms;

class LansiaResource extends \App\Filament\Resources\LansiaResource
{
    protected static ?string $navigationGroup = 'Kesehatan Lansia';
    protected static ?int    $navigationSort  = 1;

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('posyandu_id', auth()->user()->posyandu_id);
    }

    public static function form(\Filament\Forms\Form $form): \Filament\Forms\Form
    {
        $posyanduId = auth()->user()->posyandu_id;
        return parent::form($form)->schema([
            Forms\Components\Section::make('Informasi Lansia')
                ->schema([
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
                    Forms\Components\TextInput::make('no_hp')
                        ->label('No. HP')
                        ->tel()
                        ->prefix('+62')
                        ->placeholder('81234567890')
                        ->maxLength(13)
                        ->dehydrateStateUsing(fn (?string $state): ?string =>
                            $state ? '+62' . ltrim($state, '0') : null
                        )
                        ->formatStateUsing(fn (?string $state): ?string =>
                            $state ? ltrim(str_replace('+62', '', $state), '0') : null
                        ),
                ])->columns(2),
            Forms\Components\Section::make('Informasi Tambahan')
                ->schema([
                    Forms\Components\Textarea::make('alamat')
                        ->label('Alamat Lengkap')
                        ->rows(3)
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('riwayat_penyakit')
                        ->label('Riwayat Penyakit')
                        ->rows(3)
                        ->columnSpanFull(),
                ]),
        ]);
    }
    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListLansias::route('/'),
            'create' => Pages\CreateLansia::route('/create'),
            'edit'   => Pages\EditLansia::route('/{record}/edit'),
        ];
    }
}
