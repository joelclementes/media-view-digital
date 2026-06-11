<?php

use App\Http\Controllers\MediosPropagandaMovilController;
use Illuminate\Support\Facades\Route;

Route::middleware('can:ver_soportes_promocionales')->group(function () {
    Route::get('/medios-propaganda-movil', [MediosPropagandaMovilController::class, 'index'])
        ->name('m-propaganda-movil-index');

    Route::get('/medios-propaganda-movil/{registro}/testigo', [MediosPropagandaMovilController::class, 'testigo'])
        ->name('m-propaganda-movil-testigo');

    Route::get('/medios-propaganda-movil/{registro}', [MediosPropagandaMovilController::class, 'show'])
        ->name('m-propaganda-movil-show');
});
