<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BusquedaController;
use App\Http\Controllers\DragonBallController;
use App\Http\Controllers\EstadisticasController;
use App\Http\Controllers\FavoritoController;
use App\Http\Controllers\GuiaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JuegoController;
use App\Http\Controllers\MonsterHunterController;
use App\Http\Controllers\MonstruoController;
use App\Http\Controllers\PerfilController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Página principal
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])
    ->name('inicio');

/*
|--------------------------------------------------------------------------
| Buscador global (consulta juegos, guías, monstruos y personajes)
|--------------------------------------------------------------------------
*/

Route::get('/buscar', [BusquedaController::class, 'index'])
    ->name('busqueda.index');

/*
|--------------------------------------------------------------------------
| Guías y consejos
|--------------------------------------------------------------------------
*/

Route::get('/guias', [GuiaController::class, 'index'])
    ->name('guias.index');

/*
|--------------------------------------------------------------------------
| Centros de sagas
|--------------------------------------------------------------------------
|
| Estas rutas deben estar antes de /guias/{guia:slug} para evitar que
| Laravel interprete "monster-hunter" como el slug de una guía.
|
*/

Route::get(
    '/guias/monster-hunter',
    [MonsterHunterController::class, 'index']
)->name('guias.monster-hunter');

Route::get(
    '/guias/dragon-ball',
    [DragonBallController::class, 'centro']
)->name('guias.dragon-ball');

Route::get(
    '/dragon-ball/personajes',
    [DragonBallController::class, 'personajes']
)->name('dragon-ball.personajes.index');

Route::get(
    '/dragon-ball/personajes/{personaje:slug}',
    [DragonBallController::class, 'show']
)->name('dragon-ball.personajes.show');

/*
|--------------------------------------------------------------------------
| Página individual de una guía
|--------------------------------------------------------------------------
*/

Route::get(
    '/guias/{guia:slug}',
    [GuiaController::class, 'show']
)->name('guias.show');

/*
|--------------------------------------------------------------------------
| Enciclopedia de monstruos
|--------------------------------------------------------------------------
*/

Route::get(
    '/monstruos',
    [MonstruoController::class, 'index']
)->name('monstruos.index');

Route::get(
    '/monstruos/{monstruo:slug}',
    [MonstruoController::class, 'show']
)->name('monstruos.show');

/*
|--------------------------------------------------------------------------
| Videojuegos
|--------------------------------------------------------------------------
*/

Route::get(
    '/juegos',
    [JuegoController::class, 'index']
)->name('juegos.index');

Route::get(
    '/juegos/{juego}',
    [JuegoController::class, 'show']
)
    ->whereNumber('juego')
    ->name('juegos.show');

/*
|--------------------------------------------------------------------------
| Secciones del usuario
|--------------------------------------------------------------------------
*/

Route::get('/favoritos', [FavoritoController::class, 'index'])
    ->middleware('auth')
    ->name('favoritos.index');

Route::post('/favoritos/alternar', [FavoritoController::class, 'alternar'])
    ->middleware('auth')
    ->name('favoritos.alternar');

Route::get('/estadisticas', [EstadisticasController::class, 'index'])
    ->name('estadisticas.index');

Route::get('/perfil', [PerfilController::class, 'index'])
    ->middleware('auth')
    ->name('perfil.index');

Route::put('/perfil', [PerfilController::class, 'actualizar'])
    ->middleware('auth')
    ->name('perfil.actualizar');

Route::delete('/perfil', [PerfilController::class, 'destruir'])
    ->middleware('auth')
    ->name('perfil.destruir');

Route::post('/perfil/jugados/alternar', [PerfilController::class, 'alternarJugado'])
    ->middleware('auth')
    ->name('perfil.jugados.alternar');

/*
|--------------------------------------------------------------------------
| Registro e inicio de sesión
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get(
        '/registro',
        [AuthController::class, 'mostrarRegistro']
    )->name('register');

    Route::post(
        '/registro',
        [AuthController::class, 'registrar']
    )->name('register.store');

    Route::get(
        '/login',
        [AuthController::class, 'mostrarLogin']
    )->name('login');

    Route::post(
        '/login',
        [AuthController::class, 'iniciarSesion']
    )->name('login.store');
});

/*
|--------------------------------------------------------------------------
| Cerrar sesión
|--------------------------------------------------------------------------
*/

Route::post(
    '/logout',
    [AuthController::class, 'cerrarSesion']
)
    ->middleware('auth')
    ->name('logout');