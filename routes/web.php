<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ElectionController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\MesaController;
use App\Http\Controllers\ActaController;
use App\Http\Controllers\DashboardController;

// Rutas de autenticación
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Redirigir la raíz al login si no está autenticado
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Rutas protegidas (requieren autenticación)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rutas para Elecciones
    Route::resource('elections', ElectionController::class);

    // Rutas para Candidatos
    Route::resource('candidates', CandidateController::class);

    // Rutas para Mesas
    Route::resource('mesas', MesaController::class);

    // Rutas para Actas
    Route::resource('actas', ActaController::class);
    
    // Rutas para búsqueda de actas
    Route::get('/actas-search', [ActaController::class, 'search'])->name('actas.search');
    Route::post('/actas-search', [ActaController::class, 'searchResults'])->name('actas.search.results');
});
