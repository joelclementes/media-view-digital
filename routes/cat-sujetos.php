<?php
use App\Livewire\Catalogos\Sujetos\Formulario as SujetosFormulario;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/catalogos/sujetos', SujetosFormulario::class)
        ->name('catalogos.sujetos');
});
