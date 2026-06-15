<?php

use App\Http\Controllers\MediosCineController;
use Illuminate\Support\Facades\Route;

Route::middleware('can:ver_cine')->group(function () {
    Route::get('/medios-cine', [MediosCineController::class, 'index'])
        ->name('m-cine-index');

    Route::get('/medios-cine/{registro}/testigo', [MediosCineController::class, 'testigo'])
        ->name('m-cine-testigo');

    Route::get('/medios-cine/{registro}', [MediosCineController::class, 'show'])
        ->name('m-cine-show');
});
