<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetController extends Controller
{
    /**
     * Mostrar el formulario para pedir el enlace de recuperación.
     */
    public function mostrarOlvide(): View
    {
        return view('auth.olvide-clave');
    }

    /**
     * Enviar el enlace de recuperación al correo ingresado.
     *
     * Por seguridad se muestra el mismo mensaje exista o no la cuenta,
     * para no revelar qué correos están registrados.
     */
    public function enviarEnlace(Request $request): RedirectResponse
    {
        $request->validate([
            'correo' => ['required', 'email'],
        ], [
            'correo.required' => 'Debes ingresar tu correo.',
            'correo.email' => 'El correo ingresado no es válido.',
        ]);

        Password::sendResetLink(
            $request->only('correo')
        );

        return back()->with(
            'success',
            'Si el correo está registrado, te enviamos un enlace para recuperar tu clave.'
        );
    }

    /**
     * Mostrar el formulario para definir una clave nueva.
     *
     * Laravel arma el enlace del correo con el parámetro "email" fijo
     * (no usa el nombre de columna real), por eso se lee así aunque
     * el resto del formulario use "correo".
     */
    public function mostrarRestablecer(Request $request, string $token): View
    {
        return view('auth.restablecer-clave', [
            'token' => $token,
            'correo' => $request->query('email', ''),
        ]);
    }

    /**
     * Confirmar el token y guardar la clave nueva.
     *
     * No se usa Password::reset() directo: ese helper arma la
     * búsqueda del usuario con TODas las claves del arreglo
     * (retrieveByCredentials), y como esta app usa "clave" en vez de
     * "password" no lo filtra automáticamente — terminaría buscando
     * un usuario con where('clave', $claveNuevaSinCifrar), que nunca
     * existe. Se valida el token a mano con el broker en su lugar.
     */
    public function restablecer(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'token' => ['required'],
            'correo' => ['required', 'email'],
            'clave' => ['required', 'confirmed', 'min:8'],
        ], [
            'clave.confirmed' => 'Las claves no coinciden.',
            'clave.min' => 'La clave debe tener al menos 8 caracteres.',
        ]);

        $usuario = User::query()
            ->where('correo', $datos['correo'])
            ->first();

        if (!$usuario || !Password::broker()->tokenExists($usuario, $datos['token'])) {
            return back()->withErrors([
                'correo' => 'Ese enlace de recuperación no es válido o ya expiró.',
            ]);
        }

        $usuario->forceFill([
            'clave' => Hash::make($datos['clave']),
        ])->save();

        Password::broker()->deleteToken($usuario);

        event(new PasswordReset($usuario));

        return redirect()
            ->route('login')
            ->with('success', 'Tu clave fue actualizada. Ya puedes iniciar sesión.');
    }
}
