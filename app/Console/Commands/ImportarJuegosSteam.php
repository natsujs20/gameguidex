<?php

namespace App\Console\Commands;

use App\Models\Juego;
use Illuminate\Console\Command;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class ImportarJuegosSteam extends Command
{
    protected $signature = 'steam:importar-juegos
                            {--buscar= : Nombre, saga o término que se buscará}
                            {--paginas=1 : Cantidad de páginas que se importarán}
                            {--limite=50 : Cantidad máxima de juegos}';

    protected $description =
        'Buscar juegos en Steam e importarlos al catálogo de GameGuideX';

    public function handle(): int
    {
        $buscar = trim(
            (string) $this->option('buscar')
        );

        $paginas = max(
            1,
            min((int) $this->option('paginas'), 10)
        );

        $limite = max(
            1,
            min((int) $this->option('limite'), 50)
        );

        if ($buscar === '') {
            $this->error(
                'Debes indicar qué juegos quieres buscar.'
            );

            $this->line(
                'Ejemplo: php artisan steam:importar-juegos --buscar="Dragon Ball"'
            );

            return self::FAILURE;
        }

        $this->newLine();

        $this->info(
            "Buscando “{$buscar}” en Steam..."
        );

        try {
            $appIds = $this->buscarAppIds(
                $buscar,
                $paginas,
                $limite
            );
        } catch (Throwable $error) {
            $this->error(
                'No se pudo consultar el buscador de Steam.'
            );

            $this->line(
                $error->getMessage()
            );

            return self::FAILURE;
        }

        if (empty($appIds)) {
            $this->warn(
                'Steam no entregó resultados para esta búsqueda.'
            );

            return self::SUCCESS;
        }

        $this->info(
            count($appIds).' aplicaciones encontradas.'
        );

        $barra = $this->output->createProgressBar(
            count($appIds)
        );

        $barra->start();

        $creados = 0;
        $actualizados = 0;
        $omitidos = 0;
        $errores = 0;

        foreach ($appIds as $appId) {
            try {
                $resultado = $this->importarJuego(
                    $appId
                );

                match ($resultado) {
                    'creado' => $creados++,
                    'actualizado' => $actualizados++,
                    default => $omitidos++,
                };
            } catch (Throwable $error) {
                $errores++;

                $this->newLine();

                $this->warn(
                    "No se pudo importar AppID {$appId}: "
                    .$error->getMessage()
                );
            }

            $barra->advance();

            /*
             * Pausa para evitar demasiadas solicitudes
             * consecutivas contra Steam.
             */
            usleep(250000);
        }

        $barra->finish();

        $this->newLine(2);

        $this->table(
            ['Resultado', 'Cantidad'],
            [
                ['Juegos creados', $creados],
                ['Juegos actualizados', $actualizados],
                ['Resultados omitidos', $omitidos],
                ['Errores', $errores],
            ]
        );

        $this->info(
            'Importación de Steam terminada.'
        );

        return self::SUCCESS;
    }

    /**
     * Crear el cliente utilizado para consultar Steam.
     *
     * withoutVerifying() se utiliza temporalmente debido al
     * problema del certificado SSL de PHP en Windows.
     */
    private function clienteSteam(): PendingRequest
    {
        return Http::retry(
            3,
            1000
        )
            ->timeout(30)
            ->connectTimeout(15)
            ->withoutVerifying()
            ->withHeaders([
                'User-Agent' =>
                    'Mozilla/5.0 (Windows NT 10.0; Win64; x64) '
                    .'AppleWebKit/537.36 '
                    .'Chrome/150.0.0.0 Safari/537.36',

                'Accept' =>
                    'application/json,text/html',

                'Accept-Language' =>
                    'es-CL,es;q=0.9',
            ]);
    }

    /**
     * Buscar los AppID de los juegos encontrados.
     *
     * Antes se descargaba el listado completo de Steam
     * (ISteamApps/GetAppList, ~150 000 aplicaciones) y se filtraba en
     * PHP por nombre. Steam retiró ese endpoint (ahora responde 404,
     * "Method 'GetAppList' not found"), así que se usa en su lugar el
     * buscador propio de la tienda (storesearch), que además evita
     * descargar y filtrar una lista enorme para encontrar un puñado de
     * resultados.
     */
    private function buscarAppIds(
        string $buscar,
        int $paginas,
        int $limite
    ): array {
        $respuesta = $this
            ->clienteSteam()
            ->get(
                'https://store.steampowered.com/api/storesearch/',
                [
                    'term' => $buscar,
                    'cc' => 'CL',
                    'l' => 'spanish',
                ]
            );

        if ($respuesta->failed()) {
            throw new RuntimeException(
                'Steam respondió con estado HTTP '
                .$respuesta->status()
            );
        }

        $resultados = $respuesta->json('items', []);

        if (!is_array($resultados)) {
            throw new RuntimeException(
                'Steam no entregó una lista válida de resultados.'
            );
        }

        return collect($resultados)
            ->pluck('id')
            ->filter()
            ->map(
                fn ($appId) => (int) $appId
            )
            ->unique()
            ->take($limite * $paginas)
            ->values()
            ->all();
    }

    /**
     * Consultar Steam e importar un juego al catálogo.
     */
    private function importarJuego(
        int $appId
    ): string {
        $respuesta = $this
            ->clienteSteam()
            ->get(
                'https://store.steampowered.com/api/appdetails',
                [
                    'appids' => $appId,
                    'cc' => 'CL',
                    'l' => 'spanish',
                ]
            );

        if ($respuesta->failed()) {
            throw new RuntimeException(
                'Steam respondió con estado HTTP '
                .$respuesta->status()
            );
        }

        $contenido = $respuesta->json();

        if (!is_array($contenido)) {
            return 'omitido';
        }

        $respuestaSteam = $contenido[
            (string) $appId
        ] ?? null;

        if (
            !is_array($respuestaSteam)
            || !($respuestaSteam['success'] ?? false)
        ) {
            return 'omitido';
        }

        $datos = $respuestaSteam['data'] ?? [];

        /*
         * Evitar DLC, demos, videos, herramientas,
         * bandas sonoras y productos que no sean juegos.
         */
        if (($datos['type'] ?? '') !== 'game') {
            return 'omitido';
        }

        $nombre = trim(
            html_entity_decode(
                (string) ($datos['name'] ?? '')
            )
        );

        if ($nombre === '') {
            return 'omitido';
        }

        $juegoExistente = Juego::query()
            ->where('steam_app_id', $appId)
            ->orWhereLike('nombre', $nombre)
            ->first();

        /*
         * Si el nombre coincide con un juego que ya teníamos curado a
         * mano (steam_importado = false), no pisamos su descripción ni
         * demás datos: solo lo enlazamos con su AppID de Steam. Esto
         * evitó que una búsqueda genérica ("Monster Hunter") sobrescribiera
         * fichas ya revisadas manualmente.
         */
        if ($juegoExistente && !$juegoExistente->steam_importado) {
            $juegoExistente->update([
                'steam_app_id' => $appId,
                'steam_url' => "https://store.steampowered.com/app/{$appId}",
                'trailer_url' => $this->obtenerTrailer($datos['movies'] ?? []),
            ]);

            return 'omitido';
        }

        $plataformas = $this->obtenerPlataformas(
            $datos['platforms'] ?? []
        );

        $generos = collect(
            $datos['genres'] ?? []
        )
            ->pluck('description')
            ->filter()
            ->implode(', ');

        $desarrolladores = implode(
            ', ',
            $datos['developers'] ?? []
        );

        $publicadores = implode(
            ', ',
            $datos['publishers'] ?? []
        );

        $descripcion = trim(
            html_entity_decode(
                strip_tags(
                    (string) (
                        $datos['short_description']
                        ?? 'Videojuego disponible en Steam.'
                    )
                )
            )
        );

        $anio = $this->obtenerAnio(
            $datos['release_date']['date']
            ?? null
        );

        $esGratis = (bool) (
            $datos['is_free']
            ?? false
        );

        $steamUrl =
            "https://store.steampowered.com/app/{$appId}";

        $valores = [
            'nombre' => $nombre,

            'franquicia' => $this->obtenerFranquicia(
                $nombre,
                $publicadores
            ),

            'anio' => $anio,

            'plataformas' => $plataformas,

            'genero' => $generos !== ''
                ? $generos
                : 'Sin información',

            'desarrollador' => $desarrolladores !== ''
                ? $desarrolladores
                : (
                    $publicadores !== ''
                        ? $publicadores
                        : 'Sin información'
                ),

            'descripcion' => Str::limit(
                $descripcion,
                1000,
                ''
            ),

            'imagen' => $datos['header_image']
                ?? null,

            'estado_disponibilidad' => $esGratis
                ? 'Disponible gratis en Steam'
                : 'Disponible en Steam',

            'enlace_oficial' => $steamUrl,

            'texto_enlace' => $esGratis
                ? 'Jugar gratis en Steam'
                : 'Ver en Steam',

            'steam_app_id' => $appId,
            'steam_url' => $steamUrl,
            'trailer_url' => $this->obtenerTrailer($datos['movies'] ?? []),
            'steam_importado' => true,
            'steam_actualizado_at' => now(),
        ];

        if ($juegoExistente) {
            $juegoExistente->update(
                $valores
            );

            return 'actualizado';
        }

        Juego::create(
            $valores
        );

        return 'creado';
    }

    /**
     * Obtener la URL del tráiler principal desde el campo
     * "movies" que entrega Steam en appdetails.
     *
     * Steam retiró los enlaces directos a mp4/webm que entregaba
     * antes: hoy solo da manifiestos de streaming (dash_h264,
     * dash_av1, hls_h264). Se guarda hls_h264 por ser el más
     * compatible (HLS nativo en Safari/iOS); en navegadores que no
     * lo soportan de forma nativa (Chrome, Firefox) requeriría una
     * librería JS como hls.js para reproducirse en un <video> normal.
     */
    private function obtenerTrailer(array $movies): ?string
    {
        $primero = $movies[0] ?? null;

        if (!is_array($primero)) {
            return null;
        }

        return $primero['mp4']['max']
            ?? $primero['mp4']['480']
            ?? $primero['webm']['max']
            ?? $primero['webm']['480']
            ?? $primero['hls_h264']
            ?? null;
    }

    /**
     * Convertir las plataformas de Steam a texto.
     */
    private function obtenerPlataformas(
        array $plataformas
    ): string {
        $resultado = [];

        if ($plataformas['windows'] ?? false) {
            $resultado[] = 'PC';
        }

        if ($plataformas['mac'] ?? false) {
            $resultado[] = 'macOS';
        }

        if ($plataformas['linux'] ?? false) {
            $resultado[] = 'Linux';
        }

        return !empty($resultado)
            ? implode(', ', $resultado)
            : 'Sin información';
    }

    /**
     * Obtener el año del lanzamiento.
     */
    private function obtenerAnio(
        ?string $fecha
    ): int {
        if (
            $fecha
            && preg_match(
                '/\b(19|20)\d{2}\b/',
                $fecha,
                $coincidencias
            )
        ) {
            return (int) $coincidencias[0];
        }

        return (int) date('Y');
    }

    /**
     * Determinar la franquicia del juego.
     */
    private function obtenerFranquicia(
        string $nombre,
        string $publicador
    ): string {
        $franquicias = [
            'Dragon Ball' => [
                'dragon ball',
            ],

            'Monster Hunter' => [
                'monster hunter',
            ],

            'Digimon' => [
                'digimon',
            ],

            'Resident Evil' => [
                'resident evil',
                'biohazard',
            ],

            'Pokémon' => [
                'pokemon',
                'pokémon',
            ],

            'Final Fantasy' => [
                'final fantasy',
            ],

            'The Elder Scrolls' => [
                'elder scrolls',
            ],

            'Dark Souls' => [
                'dark souls',
            ],
        ];

        $nombreNormalizado = Str::lower(
            $nombre
        );

        foreach ($franquicias as $franquicia => $terminos) {
            foreach ($terminos as $termino) {
                if (
                    Str::contains(
                        $nombreNormalizado,
                        $termino
                    )
                ) {
                    return $franquicia;
                }
            }
        }

        return $publicador !== ''
            ? $publicador
            : 'Catálogo Steam';
    }
}