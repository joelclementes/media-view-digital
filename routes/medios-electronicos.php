<?php

use App\Http\Controllers\MediosElectronicosController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('can:ver_medios_electronicos')->group(function () {
    Route::get('/medios-electronicos', [MediosElectronicosController::class, 'index'])
        ->name('m-electronicos-index');

    Route::get('/medios-electronicos/{registro}/testigo', [MediosElectronicosController::class, 'testigo'])
        ->name('m-electronicos-testigo');

    Route::get('/medios-electronicos/{registro}', [MediosElectronicosController::class, 'show'])
        ->name('m-electronicos-show');
});
