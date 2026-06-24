<?php

use App\Livewire\Catalogos\TiposEleccion\Formulario as TiposEleccionFormulario;
use Illuminate\Support\Facades\Route;

Route::get('/cat-tipos-eleccion', TiposEleccionFormulario::class)
    ->name('cat-tipos-eleccion.index');
