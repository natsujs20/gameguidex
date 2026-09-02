<?php

namespace App\Http\Controllers;

use App\Models\Juego;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PerfilController extends Controller
{
    /**
     * Mostrar el perfil del usuario autenticado: sus datos, sus
     * juegos jugados y un resumen real de su actividad. Todo lo que
     * se muestra sale de conteos reales de la base de datos, nunca
     * de cifras fijas.
     */
    public function index(Request $request): View
    {
        $usuario = $request->user();

        $juegosJugados = $usuario->juegosJugados()
            ->orderByDesc('juegos_jugados.jugado_en')
            ->get();

        return view('perfil.index', [
            'juegosJugados' => $juegosJugados,

            'totalFavoritos' => $usuario->favoritos()->count(),
            'totalVisitas' => $usuario->historial()->count(),

            'actividadReciente' => $usuario->historial()
                ->with('elemento')
                ->latest('visitado_en')
                ->take(5)
                ->get()
                ->filter(fn ($visita) => $visita->elemento !== null),

            'franquiciaFavorita' => $this->franquiciaFavorita($usuario),
        ]);
    }

    /**
     * Franquicia que más se repite entre los juegos favoritos y
     * jugados del usuario. Es un dato calculado en el momento a
     * partir de actividad real, no un campo guardado ni inventado.
     */
    private function franquiciaFavorita($usuario): ?string
    {
        $franquiciasFavoritos = $usuario->favoritos()
            ->where('elemento_type', 'juego')
            ->with('elemento')
            ->get()
            ->pluck('elemento.franquicia');

        $franquiciasJugadas = $usuario->juegosJugados()
            ->pluck('franquicia');

        $masFrecuente = $franquiciasFavoritos
            ->merge($franquiciasJugadas)
            ->filter()
            ->countBy()
            ->sortDesc();

        return $masFrecuente->keys()->first();
    }

    /**
     * Actualizar nombre y correo del usuario autenticado.
     */
    public function actualizar(Request $request): RedirectResponse
    {
        $usuario = $request->user();

        $request->merge([
            'nombre' => trim((string) $request->input('nombre')),
            'correo' => Str::lower(trim((string) $request->input('correo'))),
        ]);

        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],

            'correo' => [
                'required',
                'email',
                'max:150',
                'unique:usuarios,correo,'.$usuario->id,
            ],
        ], [
            'nombre.required' => 'Debes ingresar tu nombre.',
            'correo.email' => 'El correo ingresado no es válido.',
            'correo.unique' => 'Ese correo ya está en uso por otra cuenta.',
        ]);

        $usuario->update($datos);

        return back()->with('success', 'Tus datos se actualizaron correctamente.');
    }

    /**
     * Cambiar la clave del usuario autenticado (ya con sesión
     * iniciada). Distinto del flujo de "clave olvidada": aquí se
     * pide la clave actual en vez de un enlace por correo.
     */
    public function actualizarClave(Request $request): RedirectResponse
    {
        $usuario = $request->user();

        $datos = $request->validate([
            'clave_actual' => ['required', 'string'],
            'clave_nueva' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'clave_actual.required' => 'Debes ingresar tu clave actual.',
            'clave_nueva.min' => 'La clave nueva debe tener al menos 8 caracteres.',
            'clave_nueva.confirmed' => 'Las claves nuevas no coinciden.',
        ]);

        if (!Hash::check($datos['clave_actual'], $usuario->clave)) {
            return back()->withErrors([
                'clave_actual' => 'La clave actual no es correcta.',
            ]);
        }

        $usuario->update([
            'clave' => Hash::make($datos['clave_nueva']),
        ]);

        return back()->with('success', 'Tu clave se actualizó correctamente.');
    }

    /**
     * Eliminar la cuenta del usuario autenticado. Requiere confirmar
     * la clave actual para evitar un borrado accidental. Favoritos,
     * historial y juegos jugados se borran en cascada (foreign keys).
     */
    public function destruir(Request $request): RedirectResponse
    {
        $usuario = $request->user();

        $request->validate([
            'clave' => ['required', 'string'],
        ], [
            'clave.required' => 'Debes ingresar tu clave para confirmar.',
        ]);

        if (!Hash::check($request->input('clave'), $usuario->clave)) {
            return back()->withErrors([
                'clave' => 'La clave no es correcta.',
            ]);
        }

        Auth::logout();

        $usuario->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('inicio')
            ->with('success', 'Tu cuenta fue eliminada correctamente.');
    }

    /**
     * Marca o desmarca un juego como jugado desde su ficha.
     */
    public function alternarJugado(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'juego_id' => ['required', 'integer', 'exists:juegos,id'],
        ]);

        $juego = Juego::query()->findOrFail($datos['juego_id']);

        $quedoJugado = $request->user()->alternarJugado($juego);

        return back()->with(
            'success',
            $quedoJugado ? 'Marcado como jugado.' : 'Quitado de jugados.'
        );
    }
}
