<?php

namespace App\Http\Controllers;

use App\Models\Guia;
use App\Services\CentrosInformacion;
use Illuminate\Http\Request;

class GuiaController extends Controller
{
    public function index(
        Request $request,
        CentrosInformacion $centrosInformacion
    ) {
        $buscar = trim((string) $request->input('buscar'));
        $categoria = trim((string) $request->input('categoria'));
        $orden = $request->input('orden', 'recientes');

        $guiasDestacadas = Guia::query()
            ->with('juego')
            ->publicadas()
            ->where('destacada', true)
            ->latest()
            ->take(3)
            ->get();

        $guias = Guia::query()
            ->with('juego')
            ->publicadas()
            ->buscar($buscar)
            ->when($categoria !== '', function ($query) use ($categoria) {
                $query->where('tipo', $categoria);
            })
            ->when($orden === 'populares', function ($query) {
                /*
                 * Todavía no existe una columna de visitas.
                 * Por ahora priorizamos las guías destacadas.
                 */
                $query
                    ->orderByDesc('destacada')
                    ->latest();
            })
            ->when($orden === 'nombre', function ($query) {
                $query->orderBy('titulo');
            })
            ->when(! in_array($orden, ['populares', 'nombre']), function ($query) {
                $query->latest();
            })
            ->paginate(9)
            ->withQueryString();

        $categorias = Guia::query()
            ->publicadas()
            ->select('tipo')
            ->distinct()
            ->orderBy('tipo')
            ->pluck('tipo');

        /*
         * Aquí se listan todos los Centros, incluidos los que todavía no
         * tienen contenido, porque esta página es el índice de centros.
         * La portada, en cambio, solo muestra los disponibles.
         */
        $centros = $centrosInformacion->todos();

        return view('guias.index', compact(
            'guias',
            'guiasDestacadas',
            'categorias',
            'centros',
            'buscar',
            'categoria',
            'orden'
        ));
    }

    public function show(Request $request, Guia $guia)
    {
        abort_unless($guia->publicada, 404);

        if ($request->user()) {
            $request->user()->registrarVisita($guia);
        }

        $guia->load('juego');

        $guiasRelacionadas = Guia::query()
            ->with('juego')
            ->publicadas()
            ->whereKeyNot($guia->getKey())
            ->where(function ($query) use ($guia) {
                $query
                    ->where('juego_id', $guia->juego_id)
                    ->orWhere('tipo', $guia->tipo);
            })
            ->latest()
            ->take(3)
            ->get();

        return view('guias.show', compact(
            'guia',
            'guiasRelacionadas'
        ));
    }
}