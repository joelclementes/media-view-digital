<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ViolenciaTemaController;

Route::middleware('can:administrar_catalogos')->group(function () {
    Route::get('/cat-violencia-temas', [ViolenciaTemaController::class, 'index'])
        ->name('cat-violencia-temas.index');
});