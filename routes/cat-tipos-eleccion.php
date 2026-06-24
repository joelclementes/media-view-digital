<?php

use App\Http\Controllers\TipoEleccionController;
use Illuminate\Support\Facades\Route;

Route::resource('cat-tipos-eleccion', TipoEleccionController::class)
    ->parameters([
        'cat-tipos-eleccion' => 'tipoEleccion',
    ]);