<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use DateTimeImmutable;
use Firebase\JWT\JWT;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use RuntimeException;

class AuthController extends Controller
{
    /**
     * Registrar un nuevo usuario.
     */
    public function register(
        Request $request
    ): JsonResponse {
        $datos = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
            ],

            'correo' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:usuarios,correo',
            ],

            'clave' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        $usuario = User::create([
            'nombre' => $datos['nombre'],

            'correo' => Str::lower(
                $datos['correo']
            ),

            /*
             * La clave se cifra antes de guardarla.
             */
            'clave' => Hash::make(
                $datos['clave']
            ),
        ]);

        return response()->json([
            'mensaje' => 'Usuario registrado correctamente.',

            'usuario' => [
                'id' => $usuario->id,
                'nombre' => $usuario->nombre,
                'correo' => $usuario->correo,
            ],
        ], 201);
    }

    /**
     * Iniciar sesión y generar un token JWT.
     */
    public function login(
        Request $request
    ): JsonResponse {
        $credenciales = $request->validate([
            'correo' => [
                'required',
                'string',
                'email',
            ],

            'clave' => [
                'required',
                'string',
            ],
        ]);

        $correo = Str::lower(
            $credenciales['correo']
        );

        $usuario = User::query()
            ->where('correo', $correo)
            ->first();

        if (
            !$usuario
            || !Hash::check(
                $credenciales['clave'],
                $usuario->clave
            )
        ) {
            return response()->json([
                'mensaje' => 'Las credenciales ingresadas no son válidas.',
            ], 401);
        }

        try {
            $token = $this->generarToken(
                $usuario
            );
        } catch (RuntimeException $error) {
            report($error);

            return response()->json([
                'mensaje' => 'No fue posible generar el token de acceso.',
            ], 500);
        }

        return response()->json([
            'mensaje' => 'Inicio de sesión correcto.',

            'token_type' => 'Bearer',

            'access_token' => $token,

            'expires_in' => (
                (int) config('jwt.ttl', 60)
            ) * 60,

            'usuario' => [
                'id' => $usuario->id,
                'nombre' => $usuario->nombre,
                'correo' => $usuario->correo,
            ],
        ]);
    }

    /**
     * Construir y firmar el JWT.
     */
    private function generarToken(
        User $usuario
    ): string {
        $secreto = (string) config(
            'jwt.secret'
        );

        if ($secreto === '') {
            throw new RuntimeException(
                'La variable JWT_SECRET no está configurada.'
            );
        }

        $algoritmo = (string) config(
            'jwt.algorithm',
            'HS256'
        );

        $duracion = max(
            1,
            (int) config('jwt.ttl', 60)
        );

        $ahora = new DateTimeImmutable();

        $payload = [
            /*
             * Emisor del token.
             */
            'iss' => (string) config(
                'app.url',
                'http://localhost'
            ),

            /*
             * Identificador único del token.
             */
            'jti' => (string) Str::uuid(),

            /*
             * ID del usuario autenticado.
             */
            'sub' => (string) $usuario->id,

            /*
             * Fecha de creación.
             */
            'iat' => $ahora->getTimestamp(),

            /*
             * El token es válido desde este momento.
             */
            'nbf' => $ahora->getTimestamp(),

            /*
             * Fecha de expiración.
             */
            'exp' => $ahora
                ->modify("+{$duracion} minutes")
                ->getTimestamp(),

            /*
             * Información adicional del usuario.
             */
            'usuario' => [
                'id' => $usuario->id,
                'nombre' => $usuario->nombre,
                'correo' => $usuario->correo,
            ],
        ];

        return JWT::encode(
            $payload,
            $secreto,
            $algoritmo
        );
    }
}