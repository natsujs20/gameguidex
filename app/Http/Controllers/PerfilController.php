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
     * juegos jugados y accesos a favoritos/estadísticas.
     */
    public function index(Request $request): View
    {
        $usuario = $request->user();

        return view('perfil.index', [
            'juegosJugados' => $usuario->juegosJugados()
                ->orderByDesc('juegos_jugados.jugado_en')
                ->get(),

            'totalFavoritos' => $usuario->favoritos()->count(),
        ]);
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
