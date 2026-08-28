<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Mostrar el formulario de registro.
     */
    public function mostrarRegistro(): View
    {
        return view('auth.register');
    }

    /**
     * Registrar un usuario desde la interfaz web.
     */
    public function registrar(
        Request $request
    ): RedirectResponse {
        $request->merge([
            'nombre' => trim(
                (string) $request->input('nombre')
            ),

            'correo' => Str::lower(
                trim(
                    (string) $request->input('correo')
                )
            ),
        ]);

        $datos = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:100',
            ],

            'correo' => [
                'required',
                'email',
                'max:150',
                'unique:usuarios,correo',
            ],

            'clave' => [
                'required',
                'confirmed',
                Password::min(8),
            ],
        ], [
            'nombre.required' =>
                'Debes ingresar tu nombre.',

            'nombre.max' =>
                'El nombre no puede superar los 100 caracteres.',

            'correo.required' =>
                'Debes ingresar un correo.',

            'correo.email' =>
                'El correo ingresado no es válido.',

            'correo.unique' =>
                'Ese correo ya está registrado.',

            'clave.required' =>
                'Debes ingresar una clave.',

            'clave.confirmed' =>
                'Las claves no coinciden.',

            'clave.min' =>
                'La clave debe tener al menos 8 caracteres.',
        ]);

        $usuario = User::create([
            'nombre' => $datos['nombre'],
            'correo' => $datos['correo'],

            /*
             * La clave se cifra antes de almacenarse.
             */
            'clave' => Hash::make(
                $datos['clave']
            ),
        ]);

        Auth::login(
            $usuario
        );

        $request
            ->session()
            ->regenerate();

        return redirect()
            ->route('inicio')
            ->with(
                'success',
                'Tu cuenta fue creada correctamente.'
            );
    }

    /**
     * Mostrar el formulario de inicio de sesión.
     */
    public function mostrarLogin(): View
    {
        return view('auth.login');
    }

    /**
     * Autenticar al usuario desde la interfaz web.
     */
    public function iniciarSesion(
        Request $request
    ): RedirectResponse {
        $request->merge([
            'correo' => Str::lower(
                trim(
                    (string) $request->input('correo')
                )
            ),
        ]);

        $credenciales = $request->validate([
            'correo' => [
                'required',
                'email',
            ],

            'clave' => [
                'required',
                'string',
            ],
        ], [
            'correo.required' =>
                'Debes ingresar tu correo.',

            'correo.email' =>
                'El correo ingresado no es válido.',

            'clave.required' =>
                'Debes ingresar tu clave.',
        ]);

        $usuario = User::query()
            ->where(
                'correo',
                $credenciales['correo']
            )
            ->first();

        if (
            !$usuario
            || !Hash::check(
                $credenciales['clave'],
                $usuario->clave
            )
        ) {
            return back()
                ->withErrors([
                    'correo' =>
                        'El correo o la clave son incorrectos.',
                ])
                ->onlyInput(
                    'correo'
                );
        }

        Auth::login(
            $usuario,
            $request->boolean('remember')
        );

        $request
            ->session()
            ->regenerate();

        return redirect()
            ->intended(
                route('inicio')
            )
            ->with(
                'success',
                'Sesión iniciada correctamente.'
            );
    }

    /**
     * Cerrar la sesión web.
     */
    public function cerrarSesion(
        Request $request
    ): RedirectResponse {
        Auth::logout();

        $request
            ->session()
            ->invalidate();

        $request
            ->session()
            ->regenerateToken();

        return redirect()
            ->route('inicio')
            ->with(
                'success',
                'Sesión cerrada correctamente.'
            );
    }
}