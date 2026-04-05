<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminHomeController;
use App\Http\Controllers\AdminCategoriasController;
use App\Http\Controllers\AdminPublicacionesController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\PublicacionController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\HistorialPagoController;
use App\Http\Controllers\AdminGaleriaController;
use App\Http\Controllers\AdminMetaDatosController;
use App\Http\Controllers\AdminMenuController;

// Rutas públicas
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('categoria/{url}', [CategoriaController::class, 'show'])->name('categorias.show');
Route::get('contacto', [ContactoController::class, 'index'])->name('contacto');
Route::post('contacto', [ContactoController::class, 'send'])->name('contacto.send');

// Rutas protegidas
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('historial_pagos', [HistorialPagoController::class, 'index'])->name('historial_pagos.index');
    Route::post('historial_pagos', [HistorialPagoController::class, 'store'])->name('historial_pagos.store');
    Route::get('historial_pagos/{id}', [HistorialPagoController::class, 'show'])->name('historial_pagos.show');
    Route::delete('historial_pagos/{id}', [HistorialPagoController::class, 'destroy'])->name('historial_pagos.destroy');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('dashboard', [AdminHomeController::class, 'index'])->name('dashboard');

        // Menú
        Route::get('menu', [AdminMenuController::class, 'index'])->name('menu.index');
        Route::post('menu', [AdminMenuController::class, 'store'])->name('menu.store');
        Route::patch('menu/{menu}', [AdminMenuController::class, 'update'])->name('menu.update');
        Route::delete('menu/{menu}', [AdminMenuController::class, 'destroy'])->name('menu.destroy');
        Route::post('menu/reordenar', [AdminMenuController::class, 'reordenar'])->name('menu.reordenar');

        // Categorias
        Route::get('categorias/{categoria}/hijas', [AdminCategoriasController::class, 'hijas'])->name('categorias.hijas');
        Route::patch('categorias/{categoria}/destacada', [AdminCategoriasController::class, 'toggleDestacada'])
            ->name('categorias.destacada');
        Route::resource('categorias', AdminCategoriasController::class);

        // Publicaciones
        Route::patch('publicaciones/{publicacion}/destacada', [AdminPublicacionesController::class, 'toggleDestacada'])
            ->name('publicaciones.destacada');
        Route::resource('publicaciones', AdminPublicacionesController::class)
            ->parameters(['publicaciones' => 'publicacion']);
        
            // Galería de publicaciones
        Route::post('publicaciones/{publicacion}/galeria', [AdminGaleriaController::class, 'store'])->name('publicaciones.galeria.store');
        Route::delete('publicaciones/{publicacion}/galeria/{imagen}', [AdminGaleriaController::class, 'destroy'])->name('publicaciones.galeria.destroy');
        Route::patch('publicaciones/{publicacion}/galeria/{imagen}/orden', [AdminGaleriaController::class, 'orden'])->name('publicaciones.galeria.orden');
        // Metadatos de publicaciones
        Route::post('publicaciones/{publicacion}/metadatos', [AdminMetaDatosController::class, 'store'])->name('publicaciones.metadatos.store');
        Route::delete('publicaciones/{publicacion}/metadatos/{nombre}', [AdminMetaDatosController::class, 'destroy'])->name('publicaciones.metadatos.destroy');
    });
});

// Siempre al final — captura URLs de publicaciones
Route::get('{url}', [PublicacionController::class, 'show'])->name('publicaciones.show');

require __DIR__.'/settings.php';