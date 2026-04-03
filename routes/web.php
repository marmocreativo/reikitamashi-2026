<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminHomeController;
use App\Http\Controllers\AdminCategoriasController;
use App\Http\Controllers\AdminPublicacionesController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\PublicacionController;

Route::get('/', [HomeController::class, 'index'])->name('home');
// Categorías públicas
Route::get('categorias', [CategoriaController::class, 'index'])->name('categorias.index');
Route::get('categorias/{url}', [CategoriaController::class, 'show'])->name('categorias.show');

// Publicaciones públicas
Route::get('publicaciones/{url}', [PublicacionController::class, 'show'])->name('publicaciones.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('dashboard', [AdminHomeController::class, 'index'])->name('dashboard');

        Route::get('categorias/{categoria}/hijas', [AdminCategoriasController::class, 'hijas'])->name('categorias.hijas');
        Route::resource('categorias', AdminCategoriasController::class);

        Route::resource('publicaciones', AdminPublicacionesController::class)
            ->parameters(['publicaciones' => 'publicacion']);
    });
});

require __DIR__.'/settings.php';