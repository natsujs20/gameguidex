<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class JwtMiddleware
{
    /**
     * Validar el JWT recibido en Authorization.
     */
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $token = $request->bearerToken();

        if (!$token) {
            return $this->respuestaNoAutorizada(
                'No se proporcionó un token de acceso.'
            );
        }

        $secreto = (string) config(
            'jwt.secret'
        );

        $algoritmo = (string) config(
            'jwt.algorithm',
            'HS256'
        );

        if ($secreto === '') {
            return response()->json([
                'mensaje' => 'La configuración JWT no está disponible.',
            ], 500);
        }

        try {
            $payload = JWT::decode(
                $token,
                new Key(
                    $secreto,
                    $algoritmo
                )
            );
        } catch (ExpiredException $error) {
            return $this->respuestaNoAutorizada(
                'El token de acceso ha expirado.'
            );
        } catch (Throwable $error) {
            return $this->respuestaNoAutorizada(
                'El token de acceso no es válido.'
            );
        }

        $usuarioId = isset($payload->sub)
            ? (int) $payload->sub
            : 0;

        if ($usuarioId <= 0) {
            return $this->respuestaNoAutorizada(
                'El token no contiene un usuario válido.'
            );
        }

        $usuario = User::query()
            ->find($usuarioId);

        if (!$usuario) {
            return $this->respuestaNoAutorizada(
                'El usuario asociado al token no existe.'
            );
        }

        /*
         * Permite utilizar $request->user()
         * dentro de los controladores protegidos.
         */
        $request->setUserResolver(
            fn (): User => $usuario
        );

        /*
         * Guardar también el payload para consultas futuras.
         */
        $request->attributes->set(
            'jwt_payload',
            $payload
        );

        return $next($request);
    }

    /**
     * Construir una respuesta HTTP 401.
     */
    private function respuestaNoAutorizada(
        string $mensaje
    ): JsonResponse {
        return response()->json([
            'mensaje' => $mensaje,
        ], 401);
    }
}