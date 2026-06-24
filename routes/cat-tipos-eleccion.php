<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TiposEleccionController;

Route::middleware('can:administrar_catalogos')->group(function () {
    Route::get('/cat-tipos-eleccion', [TiposEleccionController::class, 'index'])
        ->name('cat-tipos-eleccion.index');
});
