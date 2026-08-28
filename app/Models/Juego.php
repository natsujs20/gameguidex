<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Juego extends Model
{
    use HasFactory;

    protected $table = 'juegos';

    protected $fillable = [
        'nombre',
        'slug',
        'franquicia',
        'anio',
        'plataformas',
        'genero',
        'desarrollador',
        'descripcion',
        'imagen',
        'destacado',
        'estado_disponibilidad',
        'enlace_oficial',
        'texto_enlace',
        'plataforma_emulada',
        'nombre_emulador',
        'enlace_emulador',
        'steam_app_id',
        'steam_url',
        'steam_importado',
        'steam_actualizado_at',
    ];

    protected $casts = [
        'anio' => 'integer',
        'destacado' => 'boolean',
        'steam_app_id' => 'integer',
        'steam_importado' => 'boolean',
        'steam_actualizado_at' => 'datetime',
    ];

    /**
     * Mapa de imágenes locales cargado una sola vez.
     *
     * @var array<string, string>|null
     */
    private static ?array $mapaImagenes = null;

    public function guias()
    {
        return $this->hasMany(
            Guia::class
        );
    }

    public function monstruos()
    {
        return $this->hasMany(
            Monstruo::class
        );
    }

    public function materiales()
    {
        return $this->hasMany(
            Material::class
        );
    }

    /**
     * URL final de la imagen del juego.
     *
     * Primero busca una imagen local por nombre.
     * Si no existe, utiliza la URL remota guardada.
     */
    public function getImagenUrlAttribute(): ?string
    {
        $imagenGuardada = trim(
            (string) $this->imagen
        );

        /*
         * Comprobar una ruta local guardada
         * directamente en la base de datos.
         */
        if (
            $imagenGuardada !== ''
            && !Str::startsWith(
                $imagenGuardada,
                ['http://', 'https://']
            )
        ) {
            $rutaGuardada = ltrim(
                str_replace('\\', '/', $imagenGuardada),
                '/'
            );

            if (
                File::exists(
                    public_path($rutaGuardada)
                )
            ) {
                return asset(
                    $rutaGuardada
                );
            }

            $rutaDentroDeJuegos =
                'imagenes/juegos/'
                .basename($rutaGuardada);

            if (
                File::exists(
                    public_path($rutaDentroDeJuegos)
                )
            ) {
                return asset(
                    $rutaDentroDeJuegos
                );
            }
        }

        /*
         * Buscar automáticamente una imagen local
         * usando el nombre normalizado del juego.
         */
        $imagenLocal = $this->buscarImagenLocal();

        if ($imagenLocal) {
            return asset(
                $imagenLocal
            );
        }

        /*
         * Usar una imagen remota únicamente cuando
         * no exista una alternativa local.
         */
        if (
            Str::startsWith(
                $imagenGuardada,
                ['http://', 'https://']
            )
        ) {
            return $imagenGuardada;
        }

        return null;
    }

    /**
     * Determinar si existe un enlace de compra
     * o descarga oficial disponible.
     */
    public function getEstaDisponibleAttribute(): bool
    {
        if (
            trim((string) $this->enlace_oficial) === ''
        ) {
            return false;
        }

        $estado = Str::lower(
            trim(
                (string) $this->estado_disponibilidad
            )
        );

        $estadosNoDisponibles = [
            'no disponible',
            'sin venta',
            'sin descarga',
            'descontinuado',
            'retirado',
            'no posee',
        ];

        foreach ($estadosNoDisponibles as $estadoNoDisponible) {
            if (
                Str::contains(
                    $estado,
                    $estadoNoDisponible
                )
            ) {
                return false;
            }
        }

        return true;
    }

    /**
     * Buscar la portada local correspondiente.
     */
    private function buscarImagenLocal(): ?string
    {
        $mapa = self::obtenerMapaImagenes();

        if (empty($mapa)) {
            return null;
        }

        $nombreNormalizado = self::normalizarNombre(
            $this->nombre
        );

        /*
         * Coincidencia directa.
         */
        if (isset($mapa[$nombreNormalizado])) {
            return $mapa[$nombreNormalizado];
        }

        /*
         * Equivalencias para archivos cuyo nombre
         * contiene abreviaciones o errores menores.
         */
        $alias = [
            'dragonballzsparkingzero' =>
                'dragonballsparkinzero',

            'dragonballsparkingzero' =>
                'dragonballsparkinzero',

            'dragonballzkakarot' =>
                'dragonballkakarot',

            'godofwarragnarok' =>
                'godofwarragnarog',

            'reddeadredemption2' =>
                'reddeadredemptionll',

            'reddeadredemptionii' =>
                'reddeadredemptionll',

            'grandtheftautosanandreas' =>
                'grandtheftautosanandrea',

            'residentevil4remake' =>
                'residentevil4',

            'thelastofusparti' =>
                'thelastofus',

            'thelastofuspart1' =>
                'thelastofus',

            'digimonstorycybersleuth' =>
                'digimoncybersleuth',

            'digimonstorycybersleuthhackersmemory' =>
                'digimonhackersmemory',

            'digimonstorycybersleuthcompleteedition' =>
                'digimoncompleteedition',

            'digimonworldnextorder' =>
                'digimonnextorder',

            'pokemonviolet' =>
                'pokemonescarlata',

            'pokemonscarlet' =>
                'pokemonescarlata',

            'monsterhunterworldiceborne' =>
                'monsterhunterworld',
        ];

        $nombreAlternativo = $alias[
            $nombreNormalizado
        ] ?? null;

        if (
            $nombreAlternativo
            && isset($mapa[$nombreAlternativo])
        ) {
            return $mapa[$nombreAlternativo];
        }

        /*
         * Coincidencia aproximada para nombres que
         * incluyen subtítulos adicionales.
         */
        foreach ($mapa as $nombreArchivo => $ruta) {
            if (
                mb_strlen($nombreArchivo) < 7
                || mb_strlen($nombreNormalizado) < 7
            ) {
                continue;
            }

            if (
                Str::contains(
                    $nombreNormalizado,
                    $nombreArchivo
                )
                || Str::contains(
                    $nombreArchivo,
                    $nombreNormalizado
                )
            ) {
                return $ruta;
            }
        }

        return null;
    }

    /**
     * Construir el mapa de archivos disponibles.
     *
     * @return array<string, string>
     */
    private static function obtenerMapaImagenes(): array
    {
        if (self::$mapaImagenes !== null) {
            return self::$mapaImagenes;
        }

        self::$mapaImagenes = [];

        $directorio = public_path(
            'imagenes/juegos'
        );

        if (!File::isDirectory($directorio)) {
            return self::$mapaImagenes;
        }

        foreach (File::files($directorio) as $archivo) {
            $extension = Str::lower(
                $archivo->getExtension()
            );

            if (
                !in_array(
                    $extension,
                    ['jpg', 'jpeg', 'png', 'webp', 'avif'],
                    true
                )
            ) {
                continue;
            }

            $nombreNormalizado = self::normalizarNombre(
                $archivo->getFilenameWithoutExtension()
            );

            self::$mapaImagenes[$nombreNormalizado] =
                'imagenes/juegos/'
                .$archivo->getFilename();
        }

        return self::$mapaImagenes;
    }

    /**
     * Normalizar nombres para comparar archivos
     * con registros de la base de datos.
     */
    private static function normalizarNombre(
        ?string $nombre
    ): string {
        $nombre = Str::ascii(
            Str::lower(
                trim((string) $nombre)
            )
        );

        return (string) preg_replace(
            '/[^a-z0-9]+/',
            '',
            $nombre
        );
    }

    public function scopeMonsterHunter(
        Builder $query
    ): Builder {
        return $query->where(
            'franquicia',
            'Monster Hunter'
        );
    }

    public function scopeDragonBall(
        Builder $query
    ): Builder {
        return $query->where(
            'franquicia',
            'Dragon Ball'
        );
    }

    public function scopeBuscar(
        Builder $query,
        ?string $texto
    ): Builder {
        $texto = trim(
            (string) $texto
        );

        if ($texto === '') {
            return $query;
        }

        return $query->where(
            function (Builder $consulta) use ($texto) {
                $consulta
                    ->whereLike('nombre', "%{$texto}%")
                    ->orWhereLike('franquicia', "%{$texto}%")
                    ->orWhereLike('genero', "%{$texto}%")
                    ->orWhereLike('desarrollador', "%{$texto}%")
                    ->orWhereLike('descripcion', "%{$texto}%");
            }
        );
    }
}