<?php

namespace App\Http\Controllers;

use App\Models\Juego;
use App\Models\Monstruo;
use Illuminate\Http\Request;

class MonstruoController extends Controller
{
    /**
     * Mostrar el buscador y catálogo de monstruos.
     */
    public function index(Request $request)
    {
        $buscar = trim(
            (string) $request->input('buscar', '')
        );

        $juegoId = $request->filled('juego')
            ? (int) $request->input('juego')
            : null;

        $especie = trim(
            (string) $request->input('especie', '')
        );

        $orden = (string) $request->input(
            'orden',
            'destacados'
        );

        $juegos = Juego::query()
            ->monsterHunter()
            ->whereHas('monstruos', function ($query) {
                $query->publicados();
            })
            ->withCount([
                'monstruos' => function ($query) {
                    $query->publicados();
                },
            ])
            ->orderBy('anio')
            ->get();

        $especies = Monstruo::query()
            ->publicados()
            ->whereNotNull('especie')
            ->where('especie', '!=', '')
            ->select('especie')
            ->distinct()
            ->orderBy('especie')
            ->pluck('especie');

        $monstruos = Monstruo::query()
            ->with('juego')
            ->withCount([
                'materiales',
                'partes',
                'debilidades',
            ])
            ->publicados()
            ->buscar($buscar)
            ->delJuego($juegoId)
            ->when(
                $especie !== '',
                function ($query) use ($especie) {
                    $query->where('especie', $especie);
                }
            )
            ->when(
                $orden === 'nombre',
                function ($query) {
                    $query->orderBy('nombre');
                }
            )
            ->when(
                $orden === 'peligro',
                function ($query) {
                    $query
                        ->orderByDesc('nivel_peligro')
                        ->orderBy('nombre');
                }
            )
            ->when(
                !in_array($orden, ['nombre', 'peligro']),
                function ($query) {
                    $query
                        ->orderByDesc('destacado')
                        ->orderBy('nombre');
                }
            )
            ->paginate(12)
            ->withQueryString();

        $monstruosDestacados = Monstruo::query()
            ->with('juego')
            ->publicados()
            ->where('destacado', true)
            ->orderByDesc('nivel_peligro')
            ->take(4)
            ->get();

        return view('monstruos.index', compact(
            'monstruos',
            'monstruosDestacados',
            'juegos',
            'especies',
            'buscar',
            'juegoId',
            'especie',
            'orden'
        ));
    }

    /**
     * Mostrar la ficha completa de un monstruo.
     */
    public function show(Request $request, Monstruo $monstruo)
    {
        abort_unless(
            $monstruo->publicado,
            404
        );

        if ($request->user()) {
            $request->user()->registrarVisita($monstruo);
        }

        $monstruo->load([
            'juego',
            'debilidades' => function ($query) {
                $query
                    ->orderBy('tipo')
                    ->orderByDesc('intensidad')
                    ->orderBy('nombre');
            },
            'partes' => function ($query) {
                $query->orderBy('nombre');
            },
            'fuentesMateriales' => function ($query) {
                $query
                    ->with('material')
                    ->orderByRaw("
                        CASE rango
                            WHEN 'Rango bajo' THEN 1
                            WHEN 'Rango alto' THEN 2
                            WHEN 'Rango maestro' THEN 3
                            ELSE 4
                        END
                    ")
                    ->orderBy('material_id')
                    ->orderByDesc('porcentaje');
            },
        ]);

        $materialesPorRango = $monstruo
            ->fuentesMateriales
            ->groupBy('rango');

        $monstruosRelacionados = Monstruo::query()
            ->with('juego')
            ->publicados()
            ->whereKeyNot($monstruo->getKey())
            ->where(function ($query) use ($monstruo) {
                $query
                    ->where('juego_id', $monstruo->juego_id)
                    ->orWhere('especie', $monstruo->especie)
                    ->orWhere('nombre', $monstruo->nombre);
            })
            ->orderByDesc('destacado')
            ->orderBy('nombre')
            ->take(4)
            ->get();

        return view('monstruos.show', compact(
            'monstruo',
            'materialesPorRango',
            'monstruosRelacionados'
        ));
    }
}