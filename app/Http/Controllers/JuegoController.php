<?php

namespace App\Http\Controllers;

use App\Models\Juego;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class JuegoController extends Controller
{
    /**
     * Mostrar y filtrar el catálogo.
     */
    public function index(
        Request $request
    ): View {
        $buscar = trim(
            (string) $request->input('buscar', '')
        );

        $franquicia = trim(
            (string) $request->input('franquicia', '')
        );

        $plataforma = trim(
            (string) $request->input('plataforma', '')
        );

        $disponibilidad = trim(
            (string) $request->input(
                'disponibilidad',
                ''
            )
        );

        $anio = $request->filled('anio')
            ? (int) $request->input('anio')
            : null;

        $consulta = Juego::query()
            ->buscar($buscar)
            ->when(
                $franquicia !== '',
                fn ($query) =>
                    $query->where(
                        'franquicia',
                        $franquicia
                    )
            )
            ->when(
                $plataforma !== '',
                fn ($query) =>
                    $query->whereLike('plataformas', "%{$plataforma}%")
            )
            ->when(
                $disponibilidad !== '',
                fn ($query) =>
                    $query->where(
                        'estado_disponibilidad',
                        $disponibilidad
                    )
            )
            ->when(
                $anio !== null,
                fn ($query) =>
                    $query->where(
                        'anio',
                        $anio
                    )
            );

        $juegos = $consulta
            ->orderByDesc('destacado')
            ->orderByDesc('anio')
            ->orderBy('nombre')
            ->paginate(12)
            ->withQueryString();

        $franquicias = Juego::query()
            ->whereNotNull('franquicia')
            ->where('franquicia', '!=', '')
            ->select('franquicia')
            ->distinct()
            ->orderBy('franquicia')
            ->pluck('franquicia');

        $plataformas = $this
            ->obtenerPlataformas();

        $anios = Juego::query()
            ->whereNotNull('anio')
            ->select('anio')
            ->distinct()
            ->orderByDesc('anio')
            ->pluck('anio');

        $disponibilidades = Juego::query()
            ->whereNotNull(
                'estado_disponibilidad'
            )
            ->where(
                'estado_disponibilidad',
                '!=',
                ''
            )
            ->select(
                'estado_disponibilidad'
            )
            ->distinct()
            ->orderBy(
                'estado_disponibilidad'
            )
            ->pluck(
                'estado_disponibilidad'
            );

        $estadisticas = [
            'juegos' => Juego::query()->count(),

            'franquicias' => Juego::query()
                ->whereNotNull('franquicia')
                ->distinct('franquicia')
                ->count('franquicia'),

            'disponibles' => Juego::query()
                ->whereNotNull('enlace_oficial')
                ->where('enlace_oficial', '!=', '')
                ->count(),
        ];

        return view('juegos.index', [
            'juegos' => $juegos,
            'franquicias' => $franquicias,
            'plataformas' => $plataformas,
            'anios' => $anios,
            'disponibilidades' => $disponibilidades,
            'estadisticas' => $estadisticas,
        ]);
    }

    /**
     * Mostrar la ficha individual.
     */
    public function show(
        Request $request,
        Juego $juego
    ): View {
        if ($request->user()) {
            $request->user()->registrarVisita($juego);
        }

        $relacionados = Juego::query()
            ->whereKeyNot(
                $juego->getKey()
            )
            ->when(
                $juego->franquicia,
                fn ($query) =>
                    $query->where(
                        'franquicia',
                        $juego->franquicia
                    )
            )
            ->orderByDesc('destacado')
            ->orderByDesc('anio')
            ->take(4)
            ->get();

        return view('juegos.show', [
            'juego' => $juego,
            'relacionados' => $relacionados,
        ]);
    }

    /**
     * Obtener plataformas únicas desde
     * los registros existentes.
     */
    private function obtenerPlataformas(): Collection
    {
        return Juego::query()
            ->whereNotNull('plataformas')
            ->pluck('plataformas')
            ->flatMap(
                fn ($plataformas) =>
                    preg_split(
                        '/[,;|]+/',
                        (string) $plataformas
                    )
            )
            ->map(
                fn ($plataforma) =>
                    trim((string) $plataforma)
            )
            ->filter()
            ->unique(
                fn ($plataforma) =>
                    mb_strtolower($plataforma)
            )
            ->sort(
                SORT_NATURAL
                | SORT_FLAG_CASE
            )
            ->values();
    }
}