<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\SystemController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
	return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::get('/login', [SystemController::class, 'loginForm'])->name('login');
Route::post('/login', [SystemController::class, 'authenticate'])->name('login.authenticate');
Route::get('/register', [SystemController::class, 'registerForm'])->name('register');
Route::post('/register', [SystemController::class, 'registerStore'])->name('register.store');
Route::post('/logout', [SystemController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
	Route::get('/dashboard', [SystemController::class, 'dashboard'])->name('dashboard');

	Route::match(['get', 'post'], '/pronosticos', [SystemController::class, 'pronosticos'])->name('pronosticos');
	Route::match(['get', 'post'], '/indicadores', [SystemController::class, 'indicadores'])->name('indicadores');
	Route::match(['get', 'post'], '/pareto', [SystemController::class, 'pareto'])->name('pareto');
	Route::match(['get', 'post'], '/pap', [SystemController::class, 'pap'])->name('pap');
	Route::get('/historial', [SystemController::class, 'historial'])->name('historial');
	Route::delete('/historial/{calculation}', [SystemController::class, 'destroyCalculation'])->name('calculations.destroy');

    // Admin routes
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::put('/users/password', [AdminController::class, 'updatePassword'])->name('users.update-password');
        Route::get('/users/{user}/history', [AdminController::class, 'userHistory'])->name('users.history');
        Route::post('/users/{user}/toggle-admin', [AdminController::class, 'toggleAdmin'])->name('users.toggle-admin');
        Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');
    });
});
