<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeamSelectionController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Ruta de bienvenida
Route::get('/', function () {
    return view('welcome');
});

// Rutas de autenticación de Laravel Breeze
require __DIR__.'/auth.php';

// Rutas protegidas que requieren autenticación
Route::middleware(['auth'])->group(function () {
    // Ruta para seleccionar equipo (solo si no hay equipo seleccionado)
    Route::get('/select-team', [TeamSelectionController::class, 'showSelectionForm'])
        ->name('select.team.form');
        
    // Ruta para procesar la selección de equipo
    Route::post('/select-team', [TeamSelectionController::class, 'selectTeam'])
        ->name('select.team');
    
    // Ruta para obtener los equipos de una liga (AJAX)
    Route::get('/get-teams/{league}', [TeamSelectionController::class, 'getTeams']);
});

// Rutas que requieren autenticación Y equipo seleccionado
Route::middleware(['auth', 'team.selected'])->group(function () {
    // Dashboard (solo accesible con equipo seleccionado)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Rutas del dashboard
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        // Finanzas
        Route::get('/finanzas', [DashboardController::class, 'finances'])->name('finances');
        
        // Plantilla
        Route::get('/plantilla', [DashboardController::class, 'squad'])->name('squad');
        
        // Otras secciones del dashboard pueden ir aquí
    });
    
    // Perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Redirigir a la selección de equipo si no hay equipo seleccionado
Route::middleware('auth')->group(function () {
    Route::get('/home', function () {
        return redirect()->route('select.team.form');
    });
});
