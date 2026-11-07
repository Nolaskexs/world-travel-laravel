<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

// 1. Ruta raíz limpia y con nombre
Route::view('/', 'inicio')->name('inicio');

// 2. Vistas estáticas
Route::view('/login', 'login')->name('login');
Route::view('/registro', 'register')->name('register');
Route::view('/buscar', 'search')->name('search');

// 3. Vistas con controladores
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// 4. Alojamientos
Route::view('/alojamientos', 'alojamientos')->name('alojamientos');
Route::get('/alojamientos/{codigo}', function (string $codigo) {
    return view('detalle-alojamiento', compact('codigo'));
})->whereIn('codigo', ['cusco', 'lima', 'arequipa'])->name('alojamientos.detalle');

// 5. Panel de administración
Route::view('/admin', 'dashboard-admin')->name('admin.dashboard');