<?php

namespace App\Livewire\Ortu;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class Login extends Component
{
    public string $email    = '';
    public string $password = '';
    public bool   $remember = false;
    public ?string $error   = null;

    public function login(): void
    {
        $this->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Rate limiting — max 5 percobaan per menit
        $key = 'login-ortu:' . Str::lower($this->email) . '|' . request()->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            $this->error = "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik.";
            return;
        }

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            if (auth()->user()->role !== 'orang_tua') {
                Auth::logout();
                $this->error = 'Akun ini bukan akun orang tua.';
                return;
            }
            RateLimiter::clear($key);
            session()->regenerate();
            $this->redirect(route('ortu.dashboard'));
        } else {
            RateLimiter::hit($key, 60);
            $this->error = 'Email atau password salah.';
        }
    }

    public function render()
    {
        return view('livewire.ortu.login')
            ->layout('ortu.layouts.app');
    }
}
