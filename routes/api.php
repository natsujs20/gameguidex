<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GuiaController;
use App\Http\Controllers\Api\ProyectoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Autenticación pública
|--------------------------------------------------------------------------
*/

Route::post(
    '/register',
    [AuthController::class, 'register']
)
    ->middleware('throttle:5,1')
    ->name('api.register');

Route::post(
    '/login',
    [AuthController::class, 'login']
)
    ->middleware('throttle:5,1')
    ->name('api.login');

/*
|--------------------------------------------------------------------------
| Guías públicas
|--------------------------------------------------------------------------
*/

Route::get(
    '/guias/filtros',
    [GuiaController::class, 'filtros']
)->name('api.guias.filtros');

Route::get(
    '/guias',
    [GuiaController::class, 'index']
)->name('api.guias.index');

Route::get(
    '/guias/{slug}',
    [GuiaController::class, 'show']
)->name('api.guias.show');

/*
|--------------------------------------------------------------------------
| Proyectos protegidos mediante JWT
|--------------------------------------------------------------------------
*/

Route::middleware('jwt')->group(function (): void {
    Route::apiResource(
        'proyectos',
        ProyectoController::class
    );
});