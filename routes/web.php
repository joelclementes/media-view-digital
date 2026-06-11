<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MediosElectronicosController;

Route::get('/', [HomeController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    require __DIR__ . '/medios-electronicos.php';
    require __DIR__ . '/medios-impresos.php';
    require __DIR__ . '/medios-radio.php';
    require __DIR__ . '/medios-soportes-promocionales.php';
    require __DIR__ . '/medios-propaganda-movil.php';
    require __DIR__ . '/usuarios.php';
    require __DIR__ . '/roles-permisos.php';
});
