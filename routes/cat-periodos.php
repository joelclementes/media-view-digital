<?php

use App\Http\Controllers\PeriodoController;
use Illuminate\Support\Facades\Route;

Route::middleware('can:administrar_catalogos')->group(function () {
    Route::get('/cat-periodos', [PeriodoController::class, 'index'])
        ->name('cat-periodos.index');
});
