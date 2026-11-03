<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'inicio')->name('inicio');
Route::view('/login', 'login')->name('login');
Route::view('/registro', 'register')->name('register');
Route::view('/buscar', 'search')->name('search');