<?php

use App\Http\Controllers\MediosElectronicosController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('can:registrar_medios_electronicos')->group(function () {
    Route::get('/medios-electronicos', [MediosElectronicosController::class, 'index'])->name('m-electronicos-index');
});
