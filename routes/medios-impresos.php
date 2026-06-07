<?php

use App\Http\Controllers\MediosImpresosController;
use Illuminate\Support\Facades\Route;

Route::middleware('can:registrar_medios_impresos')->group(function () {
    Route::get('/medios-impresos', [MediosImpresosController::class, 'index'])
        ->name('m-impresos-index');

    Route::get('/medios-impresos/{registro}/testigo', [MediosImpresosController::class, 'testigo'])
        ->name('m-impresos-testigo');
});