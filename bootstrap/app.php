<?php

use App\Http\Middleware\JwtMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(
    basePath: dirname(__DIR__)
)
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(
        function (Middleware $middleware): void {
            $middleware->alias([
                'jwt' => JwtMiddleware::class,
            ]);

            /*
             * Railway (y la mayoría de plataformas serverless) termina el
             * TLS y reenvía las peticiones por HTTP internamente. Sin esto,
             * Laravel no confía en el header X-Forwarded-Proto y genera
             * URLs de assets/rutas como http:// aunque el visitante esté
             * en https://, lo que el navegador bloquea como "mixed content".
             */
            $middleware->trustProxies(at: '*');
        }
    )
    ->withExceptions(
        function (Exceptions $exceptions): void {
            $exceptions->shouldRenderJsonWhen(
                fn (Request $request): bool =>
                    $request->is('api/*')
            );
        }
    )
    ->create();