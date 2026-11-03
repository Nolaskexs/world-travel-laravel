<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('inicio');
});

Route::view('/', 'inicio')->name('inicio');
Route::view('/login', 'login')->name('login');
Route::view('/registro', 'register')->name('register');
Route::view('/buscar', 'search')->name('search');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');