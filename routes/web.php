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

    // Route::get('/medios-electronicos', [MediosElectronicosController::class, 'index'])->name('m-electronicos-index');
    require __DIR__ . '/medios-electronicos.php';

});
