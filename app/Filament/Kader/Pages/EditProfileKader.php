<?php

namespace App\Filament\Kader\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EditProfileKader extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationLabel  = 'Edit Profil';
    protected static ?string $navigationIcon   = 'heroicon-o-user-circle';
    protected static bool    $shouldRegisterNavigation = false;
    protected static string  $view             = 'filament.kader.pages.edit-profile-kader';

    public ?string $name             = null;
    public ?string $email            = null;
    public ?string $current_password = null;
    public ?string $new_password     = null;
    public ?string $new_password_confirmation = null;

    public function mount(): void
    {
        $user        = Auth::user();
        $this->name  = $user->name;
        $this->email = $user->email;

        $this->form->fill([
            'name'  => $user->name,
            'email' => $user->email,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Akun')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Ganti Password')
                    ->description('Kosongkan jika tidak ingin ganti password')
                    ->schema([
                        Forms\Components\TextInput::make('current_password')
                            ->label('Password Saat Ini')
                            ->password()
                            ->revealable(),
                        Forms\Components\TextInput::make('new_password')
                            ->label('Password Baru')
                            ->password()
                            ->revealable()
                            ->minLength(8)
                            ->same('new_password_confirmation'),
                        Forms\Components\TextInput::make('new_password_confirmation')
                            ->label('Konfirmasi Password Baru')
                            ->password()
                            ->revealable(),
                    ])->columns(3),
            ]);
    }

    public function save(): void
    {
        $user = Auth::user();
        $data = $this->form->getState();

        if (!empty($data['new_password'])) {
            if (empty($data['current_password'])) {
                Notification::make()
                    ->title('Gagal')
                    ->body('Masukkan password saat ini.')
                    ->danger()
                    ->send();
                return;
            }
            if (!Hash::check($data['current_password'], $user->password)) {
                Notification::make()
                    ->title('Gagal')
                    ->body('Password saat ini tidak sesuai.')
                    ->danger()
                    ->send();
                return;
            }
        }

        $user->name  = $data['name'];
        $user->email = $data['email'];

        if (!empty($data['new_password'])) {
            $user->password = Hash::make($data['new_password']);
        }

        $user->save();

        $this->form->fill([
            'name'                     => $user->name,
            'email'                    => $user->email,
            'current_password'         => null,
            'new_password'             => null,
            'new_password_confirmation'=> null,
        ]);

        Notification::make()
            ->title('Berhasil')
            ->body('Profil berhasil diperbarui.')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('kembali')
                ->label('Kembali')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(fn () => \App\Filament\Kader\Pages\Dashboard::getUrl()),
        ];
    }
}
