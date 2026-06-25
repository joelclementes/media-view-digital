<?php

use App\Http\Controllers\ReporteCapturasController;
use Illuminate\Support\Facades\Route;

Route::middleware('can:ver_reportes')->group(function () {
    Route::get('/reportes/capturas', [ReporteCapturasController::class, 'index'])
        ->name('reportes.capturas.index');

    Route::get('/reportes/capturas/pdf', [ReporteCapturasController::class, 'pdf'])
        ->name('reportes.capturas.pdf');
});
