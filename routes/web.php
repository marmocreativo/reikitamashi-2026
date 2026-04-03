<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        // Dashboard admin
        Route::get('dashboard', [App\Http\Controllers\AdminHomeController::class, 'index'])->name('dashboard');
        
        // Categorias
        Route::get('categorias/{categoria}/hijas', [App\Http\Controllers\AdminCategoriasController::class, 'hijas'])->name('categorias.hijas');
        Route::resource('categorias', App\Http\Controllers\AdminCategoriasController::class);

    });
});

require __DIR__.'/settings.php';
