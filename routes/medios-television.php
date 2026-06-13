<?php

use App\Http\Controllers\MediosTelevisionController;
use Illuminate\Support\Facades\Route;

Route::middleware('can:ver_television')->group(function () {
    Route::get('/medios-television', [MediosTelevisionController::class, 'index'])
        ->name('m-television-index');

    Route::get('/medios-television/{registro}/testigo', [MediosTelevisionController::class, 'testigo'])
        ->name('m-television-testigo');

    Route::get('/medios-television/{registro}', [MediosTelevisionController::class, 'show'])
        ->name('m-television-show');
});
