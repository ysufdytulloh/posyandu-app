<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Filament\Tables\Enums\FiltersLayout;

class UserResource extends Resource
{
    protected static ?string $model            = User::class;
    protected static ?string $navigationGroup  = 'Pengaturan';
    protected static ?string $navigationLabel  = 'Manajemen Akun';
    protected static ?string $modelLabel       = 'Manajemen Akun';
    protected static ?string $pluralModelLabel = 'Manajemen Akun';
    protected static ?int    $navigationSort   = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Akun')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255)
                            ->dehydrateStateUsing(fn (string $state): string => strtoupper($state))
                            ->extraInputAttributes(['style' => 'text-transform: uppercase']),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('password')
                            ->label('Kata Sandi')
                            ->password()
                            ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->minLength(8)
                            ->maxLength(255)
                            ->placeholder('Kosongkan jika tidak ingin mengubah kata sandi'),
                        Forms\Components\Select::make('role')
                            ->label('Role')
                            ->options([
                                'admin_desa' => 'Admin Desa',
                                'kader'      => 'Kader',
                                'orang_tua'  => 'Orang Tua',
                            ])
                            ->required()
                            ->live(),
                    ])->columns(2),

                Forms\Components\Section::make('Relasi')
                    ->schema([
                        Forms\Components\Select::make('posyandu_id')
                            ->label('Posyandu')
                            ->relationship('posyandu', 'nama')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->visible(fn (Forms\Get $get): bool =>
                                in_array($get('role'), ['kader', 'admin_desa'])
                            )
                            ->helperText('Wajib diisi untuk role Kader'),

                        Forms\Components\Select::make('ibu_id')
                            ->label('Data Ibu')
                            ->relationship('ibu', 'nama')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->visible(fn (Forms\Get $get): bool =>
                                $get('role') === 'orang_tua'
                            )
                            ->helperText('Link akun orang tua ke data ibu'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('role')
                    ->label('Role')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'admin_desa' => 'Admin Desa',
                        'kader'      => 'Kader',
                        'orang_tua'  => 'Orang Tua',
                        default      => $state,
                    })
                    ->color(fn (string $state): string => match($state) {
                        'admin_desa' => 'danger',
                        'kader'      => 'warning',
                        'orang_tua'  => 'success',
                        default      => 'gray',
                    }),
                Tables\Columns\TextColumn::make('posyandu.nama')
                    ->label('Posyandu')
                    ->getStateUsing(fn ($record) =>
                        $record->posyandu?->nama ?? $record->ibu?->posyandu?->nama ?? '-'
                    )
                    ->searchable(false),
            ])
            ->defaultSort('name')
            ->searchPlaceholder('Cari nama atau email...')
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label('Role')
                    ->options([
                        'admin_desa' => 'Admin Desa',
                        'kader'      => 'Kader',
                        'orang_tua'  => 'Orang Tua',
                    ])
                    ->native(false),
                Tables\Filters\SelectFilter::make('posyandu_id')
                    ->label('Posyandu')
                    ->relationship('posyandu', 'nama')
                    ->native(false),
            ])
            ->filtersTriggerAction(
                fn (Tables\Actions\Action $action) => $action
                    ->button()
                    ->label('Filter')
                    ->icon('heroicon-o-funnel')
            )
            ->persistFiltersInSession()
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edit Data')
                    ->button()
                    ->color('warning')
                    ->icon(null),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->button()
                    ->color('danger')
                    ->icon(null),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
