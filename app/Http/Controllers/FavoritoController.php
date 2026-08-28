<?php

namespace App\Http\Controllers;

use App\Models\Guia;
use App\Models\Juego;
use App\Models\Monstruo;
use App\Models\PersonajeDragonBall;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FavoritoController extends Controller
{
    /**
     * Tipos de contenido que se pueden marcar como favorito, junto con
     * la clase de modelo correspondiente. Mismas claves que el
     * morphMap registrado en AppServiceProvider.
     */
    private const TIPOS = [
        'juego' => Juego::class,
        'monstruo' => Monstruo::class,
        'guia' => Guia::class,
        'personaje_dragon_ball' => PersonajeDragonBall::class,
    ];

    /**
     * Lista los favoritos del usuario autenticado, agrupados por tipo
     * de contenido para poder mostrarlos en secciones separadas.
     */
    public function index(Request $request)
    {
        $favoritos = $request->user()
            ->favoritos()
            ->with('elemento')
            ->latest('created_at')
            ->get()
            ->filter(fn ($favorito) => $favorito->elemento !== null)
            ->groupBy('elemento_type');

        return view('favoritos.index', [
            'juegos' => $favoritos->get('juego', collect())->pluck('elemento'),
            'monstruos' => $favoritos->get('monstruo', collect())->pluck('elemento'),
            'guias' => $favoritos->get('guia', collect())->pluck('elemento'),
            'personajesDragonBall' => $favoritos->get('personaje_dragon_ball', collect())->pluck('elemento'),
            'total' => $favoritos->flatten(1)->count(),
        ]);
    }

    /**
     * Agrega o quita un elemento de los favoritos del usuario
     * autenticado y vuelve a la página desde la que se llamó, sin
     * necesitar JavaScript (formulario clásico con redirect back).
     */
    public function alternar(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'tipo' => 'required|string|in:'.implode(',', array_keys(self::TIPOS)),
            'id' => 'required|integer',
        ]);

        $modelo = self::TIPOS[$datos['tipo']];

        $elemento = $modelo::query()->findOrFail($datos['id']);

        $quedoFavorito = $request->user()->alternarFavorito($elemento);

        return back()->with(
            'success',
            $quedoFavorito ? 'Agregado a favoritos.' : 'Quitado de favoritos.'
        );
    }
}
