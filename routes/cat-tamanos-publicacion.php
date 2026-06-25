<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TamanosPublicacionController;

Route::middleware('can:administrar_catalogos')->group(function () {
    Route::get('/cat-tamanos-publicacion', [TamanosPublicacionController::class, 'index'])
        ->name('cat-tamanos-publicacion.index');
});