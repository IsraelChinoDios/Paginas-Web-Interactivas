<?php

use Illuminate\Support\Facades\Route;

// Ruta mínima para que el archivo no esté vacío
Route::get('/ping', fn () => ['ping' => 'pong']);
