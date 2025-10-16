<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\EmpleadoController;
use App\Http\Controllers\Admin\ClienteController;
use App\Http\Controllers\Admin\CuentaController;
use App\Http\Controllers\Cliente\HomeController as ClienteHomeController;
use App\Http\Controllers\Cliente\PagoController as ClientePagoController;
use App\Http\Controllers\Cliente\MovimientoController as ClienteMovimientoController;

// Invitados
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});

// Autenticados
Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->get('/redir', function () {
    $user = auth()->user();

    $dest = match ($user?->rol) {
        'administrador' => route('admin.home'),
        'empleado'      => route('empleado.home'),
        'cliente'       => route('cliente.home'),
        default         => route('cliente.home'),
    };

    return redirect()->to($dest);
})->name('redir');

// Dashboards por rol
Route::middleware(['auth', 'role:administrador'])->get('/admin', function () {
    return view('dashboards.admin');
})->name('admin.home');

Route::middleware(['auth', 'role:empleado'])->get('/empleado', function () {
    return view('dashboards.empleado');
})->name('empleado.home');

Route::middleware(['auth', 'role:cliente'])->get('/cliente', function () {
    return view('dashboards.cliente');
})->name('cliente.home');

// Raíz
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('redir')
        : redirect()->route('login');  // invitado → login
});

Route::prefix('admin')->middleware(['auth','role:administrador'])->group(function () {
    Route::resource('crudempleados', EmpleadoController::class)
        ->names('admin.crudempleados')
        ->parameters(['crudempleados' => 'empleado']); 

    Route::resource('crudclientes',  ClienteController::class)
        ->names('admin.crudclientes')
        ->parameters(['crudclientes' => 'cliente']);

    Route::resource('cuentas', CuentaController::class)
        ->names('admin.cuentas')
        ->parameters(['cuentas' => 'cuenta']);
});

Route::prefix('empleado')->middleware(['auth','role:empleado'])->group(function () {
    Route::get('/', fn() => view('dashboards.empleado'))->name('empleado.home');

    Route::resource('crudclientes', ClienteController::class)
         ->names('empleado.crudclientes')
         ->parameters(['crudclientes' => 'cliente']);

    Route::resource('cuentas', CuentaController::class)
         ->names('empleado.cuentas')
         ->parameters(['cuentas' => 'cuenta']);
});

Route::prefix('cliente')->middleware(['auth','role:cliente'])->group(function () {
    Route::get('/', [ClienteHomeController::class, 'index'])->name('cliente.home');

    Route::get('/pagos', [ClientePagoController::class, 'index'])->name('cliente.pagos.index');
    Route::get('/movimientos', [ClienteMovimientoController::class, 'index'])->name('cliente.movimientos.index');

    Route::get('/pagos/create', [ClientePagoController::class, 'create'])->name('cliente.pagos.create');
    Route::post('/pagos', [ClientePagoController::class, 'store'])->name('cliente.pagos.store');

    Route::get('/movimientos/create', [ClienteMovimientoController::class, 'create'])->name('cliente.movimientos.create');
    Route::post('/movimientos', [ClienteMovimientoController::class, 'store'])->name('cliente.movimientos.store');
});