<?php
namespace App\Filament\Kader\Resources;
use App\Filament\Kader\Resources\IbuResource\Pages;
use Filament\Forms;

class IbuResource extends \App\Filament\Resources\IbuResource
{
    protected static ?string $navigationGroup = 'Kesehatan Ibu & Anak';
    protected static ?int    $navigationSort  = 1;

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('posyandu_id', auth()->user()->posyandu_id);
    }

    public static function form(\Filament\Forms\Form $form): \Filament\Forms\Form
    {
        $posyanduId = auth()->user()->posyandu_id;
        return parent::form($form)->schema([
            Forms\Components\Section::make('Informasi Ibu')
                ->schema([
                    Forms\Components\Hidden::make('posyandu_id')
                        ->default($posyanduId),
                    Forms\Components\TextInput::make('nama')
                        ->label('Nama Lengkap')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('Nama lengkap ibu')
                        ->dehydrateStateUsing(fn (string $state): string => strtoupper($state))
                        ->extraInputAttributes(['style' => 'text-transform: uppercase']),
                    Forms\Components\TextInput::make('nik')
                        ->label('NIK')
                        ->inputMode('numeric')
                        ->minLength(16)
                        ->maxLength(16)
                        ->unique(ignoreRecord: true)
                        ->placeholder('16 digit NIK')
                        ->rule('digits:16'),
                    Forms\Components\DatePicker::make('tgl_lahir')
                        ->label('Tanggal Lahir')
                        ->displayFormat('d/m/Y')
                        ->maxDate(now()),
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
                    Forms\Components\Select::make('goldar')
                        ->label('Golongan Darah')
                        ->options(['A'=>'A','B'=>'B','AB'=>'AB','O'=>'O'])
                        ->native(false),
                ])->columns(2),
            Forms\Components\Section::make('Alamat')
                ->schema([
                    Forms\Components\Textarea::make('alamat')
                        ->label('Alamat Lengkap')
                        ->rows(3)
                        ->columnSpanFull(),
                ])->columns(1),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListIbus::route('/'),
            'create' => Pages\CreateIbu::route('/create'),
            'edit'   => Pages\EditIbu::route('/{record}/edit'),
        ];
    }
}
