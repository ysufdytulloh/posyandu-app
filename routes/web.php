<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin/login');
});

Route::prefix('ortu')->name('ortu.')->group(function () {
    Route::get('/login', \App\Livewire\Ortu\Login::class)->name('login');

    Route::middleware(['auth', \App\Http\Middleware\EnsureUserIsOrangTua::class])
        ->group(function () {
            Route::get('/', \App\Livewire\Ortu\Dashboard::class)->name('dashboard');
            Route::get('/anak/{id}', \App\Livewire\Ortu\DetailAnak::class)->name('detail');
            Route::get('/kehamilan', \App\Livewire\Ortu\DataKehamilanOrtu::class)->name('kehamilan');
        });
});
