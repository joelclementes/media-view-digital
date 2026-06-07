<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/usuarios', [UserController::class, 'index'])
    ->name('usuarios.index');

Route::post('/usuarios', [UserController::class, 'store'])
    ->name('usuarios.store');

Route::put('/usuarios/{user}', [UserController::class, 'update'])
    ->name('usuarios.update');