<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonajeDragonBall extends Model
{
    use HasFactory;

    protected $table = 'personajes_dragon_ball';

    protected $fillable = [
        'juego_id',
        'nombre',
        'slug',
        'personaje_base',
        'transformacion',
        'saga',
        'raza',
        'alineacion',
        'estilo_combate',
        'puntos_dp',
        'descripcion',
        'desbloqueo',
        'icono',
        'ilustracion',
        'retrato',
        'orden',
        'destacado',
        'publicado',
    ];

    protected $casts = [
        'puntos_dp' => 'integer',
        'orden' => 'integer',
        'destacado' => 'boolean',
        'publicado' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function juego()
    {
        return $this->belongsTo(Juego::class);
    }

    public function tecnicas()
    {
        return $this->hasMany(TecnicaDragonBall::class);
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

    public function scopeDeSaga(Builder $query, ?string $saga): Builder
    {
        $saga = trim((string) $saga);

        if ($saga === '') {
            return $query;
        }

        return $query->where('saga', $saga);
    }

    public function scopeDeRaza(Builder $query, ?string $raza): Builder
    {
        $raza = trim((string) $raza);

        if ($raza === '') {
            return $query;
        }

        return $query->where('raza', $raza);
    }

    public function scopeBuscar(Builder $query, ?string $texto): Builder
    {
        $texto = trim((string) $texto);

        if ($texto === '') {
            return $query;
        }

        return $query->where(function (Builder $consulta) use ($texto) {
            $consulta
                ->whereLike('nombre', "%{$texto}%")
                ->orWhereLike('personaje_base', "%{$texto}%")
                ->orWhereLike('transformacion', "%{$texto}%")
                ->orWhereLike('saga', "%{$texto}%")
                ->orWhereLike('raza', "%{$texto}%");
        });
    }

    /**
     * Otras formas del mismo personaje base (ej. Goku -> SSJ, SSJ2...),
     * para armar la "cadena de transformaciones" en la ficha individual.
     */
    public function scopeTransformacionesDe(
        Builder $query,
        self $personaje
    ): Builder {
        return $query
            ->publicados()
            ->where('juego_id', $personaje->juego_id)
            ->where('personaje_base', $personaje->personaje_base)
            ->whereKeyNot($personaje->getKey())
            ->orderBy('orden');
    }
}
