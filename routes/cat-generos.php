<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GeneroController;

Route::middleware('can:administrar_catalogos')->group(function () {
    Route::get('/catalogos/generos', [GeneroController::class, 'index'])
        ->name('catalogos.generos');
});