<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TecnicaDragonBall extends Model
{
    use HasFactory;

    protected $table = 'tecnicas_dragon_ball';

    protected $fillable = [
        'personaje_dragon_ball_id',
        'nombre',
        'tipo',
        'comando',
        'coste_ki',
        'descripcion',
    ];

    protected $casts = [
        'coste_ki' => 'integer',
    ];

    public function personaje()
    {
        return $this->belongsTo(
            PersonajeDragonBall::class,
            'personaje_dragon_ball_id'
        );
    }
}
