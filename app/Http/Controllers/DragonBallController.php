<?php

namespace App\Http\Controllers;

use App\Models\Juego;
use App\Models\PersonajeDragonBall;
use Illuminate\Http\Request;

class DragonBallController extends Controller
{
    /**
     * Centro de información de Dragon Ball (portada de la saga).
     */
    public function centro()
    {
        $estadisticas = [
            'personajes' => PersonajeDragonBall::query()
                ->publicados()
                ->count(),

            'sagas' => PersonajeDragonBall::query()
                ->publicados()
                ->whereNotNull('saga')
                ->distinct()
                ->count('saga'),

            'transformaciones' => PersonajeDragonBall::query()
                ->publicados()
                ->whereNotNull('transformacion')
                ->count(),

            'juegos' => Juego::query()
                ->dragonBall()
                ->count(),
        ];

        $personajesDestacados = PersonajeDragonBall::query()
            ->destacados()
            ->orderBy('orden')
            ->take(8)
            ->get();

        $juegos = Juego::query()
            ->dragonBall()
            ->orderBy('anio')
            ->get();

        return view('guias.dragon-ball.index', compact(
            'estadisticas',
            'personajesDestacados',
            'juegos'
        ));
    }

    /**
     * Catálogo con buscador y filtros de personajes.
     */
    public function personajes(Request $request)
    {
        $buscar = trim((string) $request->input('buscar', ''));
        $saga = trim((string) $request->input('saga', ''));
        $raza = trim((string) $request->input('raza', ''));
        $orden = (string) $request->input('orden', 'catalogo');

        $personajes = PersonajeDragonBall::query()
            ->publicados()
            ->buscar($buscar)
            ->deSaga($saga)
            ->deRaza($raza)
            ->when(
                $orden === 'nombre',
                function ($query) {
                    $query->orderBy('nombre');
                }
            )
            ->when(
                $orden !== 'nombre',
                function ($query) {
                    $query->orderBy('orden');
                }
            )
            ->paginate(24)
            ->withQueryString();

        $sagas = PersonajeDragonBall::query()
            ->publicados()
            ->whereNotNull('saga')
            ->select('saga')
            ->distinct()
            ->orderBy('saga')
            ->pluck('saga');

        $razas = PersonajeDragonBall::query()
            ->publicados()
            ->whereNotNull('raza')
            ->select('raza')
            ->distinct()
            ->orderBy('raza')
            ->pluck('raza');

        return view('dragon-ball.personajes.index', compact(
            'personajes',
            'sagas',
            'razas',
            'buscar',
            'saga',
            'raza',
            'orden'
        ));
    }

    /**
     * Ficha individual de un personaje, con sus técnicas, otras
     * transformaciones del mismo personaje base y contenido relacionado.
     */
    public function show(Request $request, PersonajeDragonBall $personaje)
    {
        abort_unless($personaje->publicado, 404);

        if ($request->user()) {
            $request->user()->registrarVisita($personaje);
        }

        $personaje->load(['juego', 'tecnicas']);

        $transformaciones = PersonajeDragonBall::query()
            ->transformacionesDe($personaje)
            ->get();

        $relacionados = PersonajeDragonBall::query()
            ->publicados()
            ->where('juego_id', $personaje->juego_id)
            ->where('personaje_base', '!=', $personaje->personaje_base)
            ->where('saga', $personaje->saga)
            ->orderBy('orden')
            ->take(4)
            ->get();

        return view('dragon-ball.personajes.show', [
            'personajeDragonBall' => $personaje,
            'transformaciones' => $transformaciones,
            'relacionados' => $relacionados,
        ]);
    }
}
