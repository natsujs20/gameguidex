<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Monstruo extends Model
{
    use HasFactory;

    protected $table = 'monstruos';

    protected $fillable = [
        'juego_id',
        'nombre',
        'slug',
        'especie',
        'elemento',
        'estado_alterado',
        'nivel_peligro',
        'descripcion',
        'habitat',
        'comportamiento',
        'estrategia',
        'imagen',
        'destacado',
        'publicado',
    ];

    protected $casts = [
        'nivel_peligro' => 'integer',
        'destacado' => 'boolean',
        'publicado' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Construye la URL pública del ícono.
     *
     * La base de datos puede guardar:
     * - Solo el archivo: MHW-Rathalos_Icon.png
     * - Una ruta antigua completa.
     * - Una URL externa.
     */
    public function getImagenUrlAttribute(): ?string
    {
        if (!$this->imagen) {
            return null;
        }

        if (Str::startsWith($this->imagen, ['http://', 'https://'])) {
            return $this->imagen;
        }

        $ruta = ltrim($this->imagen, '/');

        if (!str_contains($ruta, '/')) {
            $ruta = 'imagenes/monster-hunter/iconos/' . $ruta;
        }

        return asset($ruta);
    }

    /**
     * Inicial utilizada cuando un monstruo todavía no tiene ícono.
     */
    public function getInicialAttribute(): string
    {
        return mb_strtoupper(
            mb_substr($this->nombre, 0, 1)
        );
    }

    public function juego()
    {
        return $this->belongsTo(Juego::class);
    }

    public function fuentesMateriales()
    {
        return $this->hasMany(FuenteMaterial::class);
    }

    public function materiales()
    {
        return $this->belongsToMany(
            Material::class,
            'fuentes_materiales'
        )
            ->withPivot([
                'rango',
                'metodo',
                'parte',
                'cantidad',
                'porcentaje',
                'condicion',
                'notas',
            ])
            ->withTimestamps();
    }

    public function debilidades()
    {
        return $this->hasMany(DebilidadMonstruo::class);
    }

    public function partes()
    {
        return $this->hasMany(ParteMonstruo::class);
    }

    public function scopePublicados(Builder $query): Builder
    {
        return $query->where('publicado', true);
    }

    public function scopeDestacados(Builder $query): Builder
    {
        return $query
            ->publicados()
            ->where('destacado', true);
    }

    public function scopeDelJuego(
        Builder $query,
        ?int $juegoId
    ): Builder {
        if (!$juegoId) {
            return $query;
        }

        return $query->where('juego_id', $juegoId);
    }

    public function scopeBuscar(
        Builder $query,
        ?string $texto
    ): Builder {
        $texto = trim((string) $texto);

        if ($texto === '') {
            return $query;
        }

        return $query->where(function (Builder $consulta) use ($texto) {
            $consulta
                ->whereLike('nombre', "%{$texto}%")
                ->orWhereLike('especie', "%{$texto}%")
                ->orWhereLike('elemento', "%{$texto}%")
                ->orWhereLike('estado_alterado', "%{$texto}%")
                ->orWhereLike('descripcion', "%{$texto}%")
                ->orWhereLike('habitat', "%{$texto}%")
                ->orWhereLike('estrategia', "%{$texto}%")
                ->orWhereHas('juego', function (Builder $juegos) use ($texto) {
                    $juegos->whereLike('nombre', "%{$texto}%");
                })
                ->orWhereHas('materiales', function (Builder $materiales) use ($texto) {
                    $materiales->whereLike('nombre', "%{$texto}%");
                });
        });
    }
}
