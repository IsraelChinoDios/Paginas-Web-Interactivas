<?php

use App\Http\Controllers\FuncionController;
use App\Http\Controllers\PeliculaController;
use App\Http\Controllers\SalaController;
use App\Http\Controllers\SucursalController;
use App\Http\Controllers\Cliente\BoletoController;
use App\Http\Controllers\Cliente\DulceriaController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::resource('sucursales', SucursalController::class)->except(['show', 'create', 'edit']);
    Route::resource('salas', SalaController::class)->except(['show', 'create', 'edit']);
    Route::resource('peliculas', PeliculaController::class)->except(['show', 'create', 'edit']);
    Route::resource('funciones', FuncionController::class)->except(['show', 'create', 'edit']);

    Route::middleware(['verified', 'role:usuario,admin'])
        ->prefix('cliente')
        ->name('cliente.')
        ->group(function () {
            Route::get('cartelera', [BoletoController::class, 'index'])->name('cartelera');
            Route::post('cartelera', [BoletoController::class, 'store'])->name('cartelera.comprar');
            Route::get('cartelera/{ticket}/pdf', [BoletoController::class, 'pdf'])->name('cartelera.pdf');

            Route::get('dulceria', [DulceriaController::class, 'index'])->name('dulceria');
            Route::post('dulceria', [DulceriaController::class, 'store'])->name('dulceria.comprar');
            Route::get('dulceria/{order}/pdf', [DulceriaController::class, 'pdf'])->name('dulceria.pdf');
        });

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

require __DIR__.'/auth.php';
