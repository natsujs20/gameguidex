<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EstadisticasController extends Controller
{
    /**
     * Muestra la actividad real del usuario autenticado (favoritos por
     * tipo y últimos elementos visitados). Si no hay sesión iniciada,
     * o el usuario todavía no tiene actividad, se mantiene el estado
     * vacío honesto que ya existía en vez de inventar cifras.
     */
    public function index(Request $request)
    {
        $usuario = $request->user();

        if (! $usuario) {
            return view('estadisticas.index', [
                'autenticado' => false,
                'totalFavoritos' => 0,
                'favoritosPorTipo' => collect(),
                'ultimasVisitas' => collect(),
            ]);
        }

        $favoritosPorTipo = $usuario->favoritos()
            ->selectRaw('elemento_type, count(*) as total')
            ->groupBy('elemento_type')
            ->pluck('total', 'elemento_type');

        $etiquetasTipo = [
            'juego' => 'Videojuegos',
            'monstruo' => 'Monstruos',
            'guia' => 'Guías',
            'personaje_dragon_ball' => 'Personajes de Dragon Ball',
        ];

        $ultimasVisitas = $usuario->historial()
            ->with('elemento')
            ->latest('visitado_en')
            ->take(10)
            ->get()
            ->filter(fn ($visita) => $visita->elemento !== null);

        return view('estadisticas.index', [
            'autenticado' => true,
            'totalFavoritos' => (int) $favoritosPorTipo->sum(),
            'favoritosPorTipo' => $favoritosPorTipo->mapWithKeys(
                fn ($total, $tipo) => [($etiquetasTipo[$tipo] ?? $tipo) => $total]
            ),
            'ultimasVisitas' => $ultimasVisitas,
        ]);
    }
}
