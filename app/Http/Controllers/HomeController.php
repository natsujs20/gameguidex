<?php

namespace App\Http\Controllers;

use App\Models\Guia;
use App\Models\Juego;
use App\Services\CentrosInformacion;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Mostrar la página principal de GameGuideX.
     *
     * Los Centros de Información ya no se escriben a mano aquí: se leen
     * del registro de config/centros.php y el servicio les añade sus
     * contadores reales.
     */
    public function index(CentrosInformacion $centrosInformacion): View
    {
        $juegosDestacados = Juego::query()
            ->orderByDesc('anio')
            ->limit(4)
            ->get();

        $guiasDestacadas = Guia::query()
            ->with('juego')
            ->publicadas()
            ->where('destacada', true)
            ->latest()
            ->take(3)
            ->get();

        return view('welcome', [
            'juegosDestacados' => $juegosDestacados,
            'guiasDestacadas' => $guiasDestacadas,
            'centros' => $centrosInformacion->disponibles(),
        ]);
    }
}
