<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\adminController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home',[adminController::class,'index'])->name('home');
Route::post('/register',[adminController::class,'register'])->name('register');