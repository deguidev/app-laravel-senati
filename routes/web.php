<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MarcaController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('hola-mundo', function () {
    return Inertia::render('HolaMundo');
})->middleware(['auth', 'verified'])->name('hola-mundo');

Route::get('categories', function () {
    return Inertia::render('Categories/Index');
})->middleware(['auth', 'verified'])->name('categories');

// Rutas API para Categorías (usando Axios)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('categories-data', [CategoryController::class, 'index'])->name('categories.data');
    Route::post('categories-data', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('categories-data/{category}', [CategoryController::class, 'show'])->name('categories.show');
    Route::put('categories-data/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('categories-data/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
});

// Rutas para Marcas (usando Inertia)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('marcas', MarcaController::class);
});











require __DIR__.'/settings.php';
