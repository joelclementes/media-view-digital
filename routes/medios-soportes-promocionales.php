<?php

use App\Http\Controllers\MediosSoportesPromocionalesController;
use Illuminate\Support\Facades\Route;

Route::middleware('can:ver_soportes_promocionales')->group(function () {
    Route::get('/medios-soportes-promocionales', [MediosSoportesPromocionalesController::class, 'index'])
        ->name('m-soportes-promocionales-index');

    Route::get('/medios-soportes-promocionales/{registro}/testigo', [MediosSoportesPromocionalesController::class, 'testigo'])
        ->name('m-soportes-promocionales-testigo');

    Route::get('/medios-soportes-promocionales/{registro}', [MediosSoportesPromocionalesController::class, 'show'])
        ->name('m-soportes-promocionales-show');
});
