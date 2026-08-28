<?php

namespace App\Http\Controllers;

use App\Models\Guia;
use App\Models\Juego;
use App\Models\Material;
use App\Models\Monstruo;

class MonsterHunterController extends Controller
{
    public function index()
    {
        $juegos = Juego::query()
            ->monsterHunter()
            ->withCount([
                'monstruos' => function ($query) {
                    $query->publicados();
                },
                'materiales' => function ($query) {
                    $query->publicados();
                },
                'guias' => function ($query) {
                    $query->publicadas();
                },
            ])
            ->orderBy('anio')
            ->get();

        $monstruosDestacados = Monstruo::query()
            ->with('juego')
            ->publicados()
            ->whereHas('juego', function ($query) {
                $query->monsterHunter();
            })
            ->orderByDesc('destacado')
            ->orderByDesc('nivel_peligro')
            ->take(6)
            ->get();

        $guiasDestacadas = Guia::query()
            ->with('juego')
            ->publicadas()
            ->whereHas('juego', function ($query) {
                $query->monsterHunter();
            })
            ->orderByDesc('destacada')
            ->latest()
            ->take(4)
            ->get();

        $estadisticas = [
            'juegos' => $juegos->count(),

            'monstruos' => Monstruo::query()
                ->publicados()
                ->whereHas('juego', function ($query) {
                    $query->monsterHunter();
                })
                ->count(),

            'materiales' => Material::query()
                ->publicados()
                ->whereHas('juego', function ($query) {
                    $query->monsterHunter();
                })
                ->count(),

            'guias' => Guia::query()
                ->publicadas()
                ->whereHas('juego', function ($query) {
                    $query->monsterHunter();
                })
                ->count(),
        ];

        return view(
            'guias.monster-hunter.index',
            compact(
                'juegos',
                'monstruosDestacados',
                'guiasDestacadas',
                'estadisticas'
            )
        );
    }
}