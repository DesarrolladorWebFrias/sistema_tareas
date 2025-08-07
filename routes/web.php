<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aquí es donde puedes registrar las rutas web para tu aplicación. Estos
| archivos son cargados por el RouteServiceProvider.
| Ahora crea algo genial!
|
*/

// La ruta raíz de la aplicación
Route::get('/', function () {
    return view('welcome');
});

// Ruta del dashboard, protegida por el middleware de autenticación
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Grupo de rutas que requieren que el usuario esté autenticado
Route::middleware('auth')->group(function () {
    // Rutas de recursos para las tareas (CRUD)
    Route::resource('tasks', TaskController::class);

    // Rutas de recursos para las categorías (CRUD)
    Route::resource('categories', CategoryController::class);

    // Rutas para la gestión del perfil de usuario (ya creadas por Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Incluye las rutas de autenticación de Breeze (login, register, etc.)
require __DIR__.'/auth.php';
