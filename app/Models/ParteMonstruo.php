<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParteMonstruo extends Model
{
    use HasFactory;

    protected $table = 'partes_monstruos';

    protected $fillable = [
        'monstruo_id',
        'nombre',
        'rompible',
        'cortable',
        'mejor_tipo_dano',
        'debilidad_corte',
        'debilidad_impacto',
        'debilidad_disparo',
        'recompensa_especial',
        'consejos',
    ];

    protected $casts = [
        'rompible' => 'boolean',
        'cortable' => 'boolean',
        'debilidad_corte' => 'integer',
        'debilidad_impacto' => 'integer',
        'debilidad_disparo' => 'integer',
    ];

    public function monstruo()
    {
        return $this->belongsTo(Monstruo::class);
    }
}