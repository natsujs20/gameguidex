<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Proyecto extends Model
{
    use HasFactory;

    /**
     * Tabla asociada al modelo.
     */
    protected $table = 'proyectos';

    /**
     * Atributos permitidos para asignación masiva.
     */
    protected $fillable = [
        'nombre',
        'descripcion',
        'created_by',
    ];

    /**
     * Conversiones automáticas de atributos.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_by' => 'integer',
        ];
    }

    /**
     * Usuario que creó el proyecto.
     */
    public function creador(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }
}