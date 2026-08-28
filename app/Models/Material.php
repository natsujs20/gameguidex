<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $table = 'materiales';

    protected $fillable = [
        'juego_id',
        'nombre',
        'slug',
        'rareza',
        'descripcion',
        'usos',
        'imagen',
        'publicado',
    ];

    protected $casts = [
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

    public function fuentes()
    {
        return $this->hasMany(FuenteMaterial::class);
    }

    public function monstruos()
    {
        return $this->belongsToMany(
            Monstruo::class,
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

    public function scopePublicados(Builder $query): Builder
    {
        return $query->where('publicado', true);
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
                ->orWhereLike('rareza', "%{$texto}%")
                ->orWhereLike('descripcion', "%{$texto}%")
                ->orWhereLike('usos', "%{$texto}%")
                ->orWhereHas('juego', function (Builder $juegos) use ($texto) {
                    $juegos->whereLike('nombre', "%{$texto}%");
                })
                ->orWhereHas('monstruos', function (Builder $monstruos) use ($texto) {
                    $monstruos->whereLike('nombre', "%{$texto}%");
                });
        });
    }
}