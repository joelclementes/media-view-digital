<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/en-construccion', function () {
    return view('auth.inactivo');
})->middleware('auth')->name('cuenta.inactiva');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'usuario.activo',
])->group(function () {

    Route::get('/', [HomeController::class, 'index'])
        ->name('dashboard');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard.jetstream');

    require __DIR__ . '/medios-electronicos.php';
    require __DIR__ . '/medios-impresos.php';
    require __DIR__ . '/medios-radio.php';
    require __DIR__ . '/medios-soportes-promocionales.php';
    require __DIR__ . '/medios-propaganda-movil.php';
    require __DIR__ . '/medios-television.php';
    require __DIR__ . '/medios-cine.php';
    require __DIR__ . '/usuarios.php';
    require __DIR__ . '/roles-permisos.php';

    // Catálogos
    require __DIR__ . '/cat-tipos-eleccion.php';
    require __DIR__ . '/cat-sujetos.php';
    require __DIR__ . '/cat-tamanos-publicacion.php';
    require __DIR__ . '/cat-generos.php';
    require __DIR__ . '/cat-violencia-temas.php';
    require __DIR__ . '/cat-periodos.php';

    // Reportes
    require __DIR__ . '/reportes-capturas.php';

});
