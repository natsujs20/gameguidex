<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Guia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GuiaController extends Controller
{
    /**
     * Listar y buscar guías.
     *
     * Ejemplos:
     * /api/guias
     * /api/guias?buscar=Gema de Teostra
     * /api/guias?juego=Monster Hunter World
     * /api/guias?tipo=Material
     */
    public function index(Request $request): JsonResponse
    {
        $guias = Guia::query()
            ->with('juego')
            ->publicadas()
            ->buscar($request->query('buscar'));

        if ($request->filled('juego')) {
            $juego = trim($request->query('juego'));

            $guias->whereHas('juego', function ($consulta) use ($juego) {
                $consulta
                    ->whereLike('nombre', "%{$juego}%")
                    ->orWhereLike('franquicia', "%{$juego}%");
            });
        }

        if ($request->filled('tipo')) {
            $guias->where('tipo', $request->query('tipo'));
        }

        if ($request->filled('plataforma')) {
            $guias->whereLike(
                'plataformas',
                '%' . trim($request->query('plataforma')) . '%'
            );
        }

        if ($request->filled('dificultad')) {
            $guias->where('dificultad', $request->query('dificultad'));
        }

        if ($request->boolean('destacadas')) {
            $guias->where('destacada', true);
        }

        $resultados = $guias
            ->orderByDesc('destacada')
            ->orderBy('titulo')
            ->paginate(12)
            ->withQueryString();

        return response()->json([
            'correcto' => true,
            'mensaje' => 'Guías obtenidas correctamente.',
            'total' => $resultados->total(),
            'datos' => $resultados->items(),
            'paginacion' => [
                'pagina_actual' => $resultados->currentPage(),
                'ultima_pagina' => $resultados->lastPage(),
                'por_pagina' => $resultados->perPage(),
                'total' => $resultados->total(),
            ],
        ]);
    }

    /**
     * Mostrar una guía usando su slug.
     *
     * Ejemplo:
     * /api/guias/gema-de-teostra
     */
    public function show(string $slug): JsonResponse
    {
        $guia = Guia::query()
            ->with('juego')
            ->publicadas()
            ->where('slug', $slug)
            ->first();

        if (!$guia) {
            return response()->json([
                'correcto' => false,
                'mensaje' => 'La guía solicitada no existe.',
            ], 404);
        }

        return response()->json([
            'correcto' => true,
            'datos' => $guia,
        ]);
    }

    /**
     * Obtener las opciones disponibles para los filtros.
     */
    public function filtros(): JsonResponse
    {
        return response()->json([
            'correcto' => true,
            'datos' => [
                'tipos' => Guia::query()
                    ->publicadas()
                    ->select('tipo')
                    ->distinct()
                    ->orderBy('tipo')
                    ->pluck('tipo'),

                'dificultades' => Guia::query()
                    ->publicadas()
                    ->select('dificultad')
                    ->distinct()
                    ->orderBy('dificultad')
                    ->pluck('dificultad'),
            ],
        ]);
    }
}