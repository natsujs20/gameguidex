<?php

namespace App\Http\Controllers;

use App\Models\Guia;
use App\Models\Juego;
use App\Models\Monstruo;
use App\Models\PersonajeDragonBall;
use Illuminate\Http\Request;

class BusquedaController extends Controller
{
    /**
     * Buscador global: un solo cuadro de texto que consulta todos los
     * tipos de contenido navegables (juegos, guías, monstruos y
     * personajes de Dragon Ball) en vez de estar limitado a un Centro.
     *
     * Reutiliza el scope buscar() que cada modelo ya tenía para su
     * propio buscador interno, así que los resultados aquí son
     * consistentes con lo que cada sección devuelve por separado.
     */
    public function index(Request $request)
    {
        $buscar = trim((string) $request->input('buscar', ''));

        $resultadosVacios = $buscar === '';

        $limite = 6;

        $juegos = $resultadosVacios
            ? collect()
            : Juego::query()->buscar($buscar)->orderBy('nombre')->take($limite)->get();

        $guias = $resultadosVacios
            ? collect()
            : Guia::query()->with('juego')->publicadas()->buscar($buscar)->latest()->take($limite)->get();

        $monstruos = $resultadosVacios
            ? collect()
            : Monstruo::query()->publicados()->buscar($buscar)->orderBy('nombre')->take($limite)->get();

        $personajesDragonBall = $resultadosVacios
            ? collect()
            : PersonajeDragonBall::query()->publicados()->buscar($buscar)->orderBy('orden')->take($limite)->get();

        $totalResultados = $juegos->count()
            + $guias->count()
            + $monstruos->count()
            + $personajesDragonBall->count();

        return view('busqueda.index', compact(
            'buscar',
            'juegos',
            'guias',
            'monstruos',
            'personajesDragonBall',
            'totalResultados'
        ));
    }
}
