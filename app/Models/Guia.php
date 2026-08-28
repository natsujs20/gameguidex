<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guia extends Model
{
    use HasFactory;

    protected $table = 'guias';

    protected $fillable = [
        'juego_id',
        'titulo',
        'slug',
        'tipo',
        'descripcion',
        'donde_conseguir',
        'pasos',
        'requisitos',
        'consejos',
        'plataformas',
        'dificultad',
        'palabras_clave',
        'imagen',
        'destacada',
        'publicada',
    ];

    protected $casts = [
        'destacada' => 'boolean',
        'publicada' => 'boolean',
    ];

    public function juego()
    {
        return $this->belongsTo(Juego::class);
    }

    public function scopePublicadas(Builder $query): Builder
    {
        return $query->where('publicada', true);
    }

    public function scopeBuscar(Builder $query, ?string $texto): Builder
    {
        $texto = trim((string) $texto);

        if ($texto === '') {
            return $query;
        }

        return $query->where(function (Builder $consulta) use ($texto) {
            $consulta
                ->whereLike('titulo', "%{$texto}%")
                ->orWhereLike('tipo', "%{$texto}%")
                ->orWhereLike('descripcion', "%{$texto}%")
                ->orWhereLike('donde_conseguir', "%{$texto}%")
                ->orWhereLike('pasos', "%{$texto}%")
                ->orWhereLike('requisitos', "%{$texto}%")
                ->orWhereLike('consejos', "%{$texto}%")
                ->orWhereLike('palabras_clave', "%{$texto}%")
                ->orWhereHas('juego', function (Builder $juegos) use ($texto) {
                    $juegos
                        ->whereLike('nombre', "%{$texto}%")
                        ->orWhereLike('franquicia', "%{$texto}%");
                });
        });
    }
}