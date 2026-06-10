<?php

use App\Http\Controllers\MediosRadioController;
use Illuminate\Support\Facades\Route;

Route::middleware('can:ver_radio')->group(function () {
    Route::get('/medios-radio', [MediosRadioController::class, 'index'])
        ->name('m-radio-index');

    Route::get('/medios-radio/{registro}/testigo', [MediosRadioController::class, 'testigo'])
        ->name('m-radio-testigo');

    Route::get('/medios-radio/{registro}', [MediosRadioController::class, 'show'])
        ->name('m-radio-show');
});
