<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\UsuariosPortales;
use App\Livewire\AsignacionPortales\AsignacionPortales;
use Illuminate\Support\Facades\Route;

Route::get('/usuarios', [UserController::class, 'index'])
    ->name('usuarios.index');

Route::post('/usuarios', [UserController::class, 'store'])
    ->name('usuarios.store');

Route::put('/usuarios/{user}', [UserController::class, 'update'])
    ->name('usuarios.update');

Route::get('/usuarios/asignar-portales', [UsuariosPortales::class, 'index'])
    ->middleware(['auth'])
    ->name('asignar-portales');

Route::patch('/usuarios/{user}/estado', [UserController::class, 'cambiarEstado'])
    ->name('usuarios.estado');
