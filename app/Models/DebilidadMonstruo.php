<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DebilidadMonstruo extends Model
{
    use HasFactory;

    protected $table = 'debilidades_monstruos';

    protected $fillable = [
        'monstruo_id',
        'tipo',
        'nombre',
        'intensidad',
        'parte',
        'notas',
    ];

    protected $casts = [
        'intensidad' => 'integer',
    ];

    public function monstruo()
    {
        return $this->belongsTo(Monstruo::class);
    }

    public function getEstrellasAttribute(): string
    {
        $intensidad = max(
            0,
            min(3, (int) $this->intensidad)
        );

        return str_repeat('★', $intensidad)
            . str_repeat('☆', 3 - $intensidad);
    }
}