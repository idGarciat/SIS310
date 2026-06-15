<?php

use App\Http\Controllers\SystemController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
	return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::get('/login', [SystemController::class, 'loginForm'])->name('login');
Route::post('/login', [SystemController::class, 'authenticate'])->name('login.authenticate');
Route::post('/logout', [SystemController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
	Route::get('/dashboard', [SystemController::class, 'dashboard'])->name('dashboard');

	Route::match(['get', 'post'], '/pronosticos', [SystemController::class, 'pronosticos'])->name('pronosticos');
	Route::match(['get', 'post'], '/indicadores', [SystemController::class, 'indicadores'])->name('indicadores');
	Route::match(['get', 'post'], '/pareto', [SystemController::class, 'pareto'])->name('pareto');
	Route::match(['get', 'post'], '/pap', [SystemController::class, 'pap'])->name('pap');
	Route::get('/historial', [SystemController::class, 'historial'])->name('historial');
});
