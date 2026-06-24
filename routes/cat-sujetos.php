<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SujetoController;

Route::middleware('can:administrar_catalogos')->group(function () {
    Route::get('/catalogos/sujetos', [SujetoController::class, 'index'])
        ->name('catalogos.sujetos');
});
