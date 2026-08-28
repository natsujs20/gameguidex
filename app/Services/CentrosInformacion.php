<?php

namespace App\Services;

use App\Models\Guia;
use App\Models\Juego;
use App\Models\Material;
use App\Models\Monstruo;
use App\Models\PersonajeDragonBall;
use App\Models\TecnicaDragonBall;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Resuelve los Centros de Información declarados en config/centros.php
 * y los completa con datos reales de la base de datos: número de
 * elementos por categoría e imagen de portada.
 *
 * Las vistas nunca consultan la base de datos: reciben desde el
 * controlador el resultado ya preparado por este servicio.
 */
class CentrosInformacion
{
    /**
     * Tipos de contenido que se saben contar, y cómo hacerlo.
     *
     * Cada entrada devuelve una consulta que ya filtra por "publicado"
     * y que se agrupa después por la franquicia del videojuego. Para
     * añadir un tipo nuevo (armas, misiones...) basta con registrarlo
     * aquí y usar su clave en config/centros.php.
     *
     * @var array<string, class-string>
     */
    public const RECURSOS = [
        'monstruos' => Monstruo::class,
        'materiales' => Material::class,
        'guias' => Guia::class,
        'personajes' => PersonajeDragonBall::class,
        'tecnicas' => TecnicaDragonBall::class,
    ];

    /**
     * Conteos ya calculados, para no repetir consultas si en una misma
     * petición se piden los Centros más de una vez (portada + sidebar).
     *
     * @var array<string, array<string, int>>|null
     */
    private ?array $conteos = null;

    /**
     * Imágenes de portada por franquicia.
     *
     * @var array<string, string|null>|null
     */
    private ?array $portadas = null;

    /**
     * Todos los Centros declarados, con sus datos reales.
     */
    public function todos(): Collection
    {
        return collect(config('centros.centros', []))
            ->map(fn (array $centro): array => $this->completar($centro));
    }

    /**
     * Solo los Centros que ya tienen página propia. Es lo que se usa
     * en el sidebar y en la portada.
     */
    public function disponibles(): Collection
    {
        return $this->todos()
            ->filter(fn (array $centro): bool => $centro['disponible'])
            ->values();
    }

    /**
     * Añade a un Centro declarado su contenido real: portada, conteos
     * por categoría y total de elementos.
     *
     * @param  array<string, mixed>  $centro
     * @return array<string, mixed>
     */
    private function completar(array $centro): array
    {
        $franquicia = $centro['franquicia'];

        $categorias = collect($centro['categorias'] ?? [])
            ->map(function (array $categoria) use ($franquicia): array {
                $recurso = $categoria['recurso'] ?? null;

                return [
                    'nombre' => $categoria['nombre'],
                    'recurso' => $recurso,
                    'total' => $recurso
                        ? $this->contar($recurso, $franquicia)
                        : null,
                    'url' => isset($categoria['ruta'])
                        ? route($categoria['ruta'])
                        : null,
                ];
            })
            /*
             * Una categoría con contador en cero es una sección vacía:
             * se oculta en vez de anunciar contenido que no existe.
             */
            ->reject(fn (array $categoria): bool => $categoria['total'] === 0)
            ->values()
            ->all();

        return [
            'slug' => $centro['slug'],
            'nombre' => $centro['nombre'],
            'descripcion' => $centro['descripcion'],
            'disponible' => (bool) ($centro['disponible'] ?? false),
            'url' => isset($centro['ruta'])
                ? route($centro['ruta'])
                : null,
            'imagen' => $this->portada($franquicia),
            'categorias' => $categorias,
        ];
    }

    /**
     * Número de elementos publicados de un tipo dentro de una franquicia.
     */
    private function contar(string $recurso, string $franquicia): int
    {
        return $this->conteos()[$recurso][$franquicia] ?? 0;
    }

    /**
     * Calcula de una vez los conteos de todos los tipos de contenido,
     * agrupados por franquicia.
     *
     * Se hace una consulta por tipo de contenido (no una por Centro),
     * así añadir Centros nuevos no añade consultas.
     *
     * @return array<string, array<string, int>>
     */
    private function conteos(): array
    {
        if ($this->conteos !== null) {
            return $this->conteos;
        }

        $this->conteos = [];

        foreach (self::RECURSOS as $recurso => $modelo) {
            $consulta = $this->consultaPublicados($modelo);

            /*
             * Las técnicas no apuntan al videojuego directamente: hay
             * que pasar por el personaje al que pertenecen.
             */
            if ($modelo === TecnicaDragonBall::class) {
                $consulta->join(
                    'personajes_dragon_ball',
                    'personajes_dragon_ball.id',
                    '=',
                    'tecnicas_dragon_ball.personaje_dragon_ball_id'
                );

                $columnaJuego = 'personajes_dragon_ball.juego_id';
            } else {
                $columnaJuego = (new $modelo)->getTable().'.juego_id';
            }

            $this->conteos[$recurso] = $consulta
                ->join('juegos', 'juegos.id', '=', $columnaJuego)
                ->whereNotNull('juegos.franquicia')
                ->groupBy('juegos.franquicia')
                ->selectRaw('juegos.franquicia as franquicia, count(*) as total')
                ->pluck('total', 'franquicia')
                ->map(fn ($total): int => (int) $total)
                ->all();
        }

        return $this->conteos;
    }

    /**
     * Aplica el filtro de "publicado" propio de cada modelo. No todos
     * usan el mismo nombre de scope (publicados / publicadas) y las
     * técnicas de Dragon Ball no tienen ese concepto.
     *
     * @param  class-string  $modelo
     */
    private function consultaPublicados(string $modelo): Builder
    {
        $consulta = $modelo::query();

        if ($modelo === Guia::class) {
            return $consulta->publicadas();
        }

        if ($modelo === TecnicaDragonBall::class) {
            return $consulta;
        }

        return $consulta->publicados();
    }

    /**
     * Columna que enlaza cada modelo con su videojuego. Las técnicas
     * de Dragon Ball no apuntan al juego directamente, sino a través
     * del personaje.
     *
     * @param  class-string  $modelo
     */
    private function columnaJuego(string $modelo): string
    {
        return $modelo === TecnicaDragonBall::class
            ? 'personajes_dragon_ball.juego_id'
            : (new $modelo)->getTable().'.juego_id';
    }

    /**
     * Imagen de portada del Centro: se toma del videojuego más reciente
     * de esa franquicia que tenga imagen.
     */
    private function portada(string $franquicia): ?string
    {
        if ($this->portadas === null) {
            $this->portadas = [];
        }

        if (array_key_exists($franquicia, $this->portadas)) {
            return $this->portadas[$franquicia];
        }

        $juego = Juego::query()
            ->where('franquicia', $franquicia)
            ->whereNotNull('imagen')
            ->orderByDesc('anio')
            ->first();

        return $this->portadas[$franquicia] = $juego?->imagen_url;
    }
}
