<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuenteMaterial extends Model
{
    use HasFactory;

    protected $table = 'fuentes_materiales';

    protected $fillable = [
        'monstruo_id',
        'material_id',
        'rango',
        'metodo',
        'parte',
        'cantidad',
        'porcentaje',
        'condicion',
        'notas',
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'porcentaje' => 'decimal:2',
    ];

    public function monstruo()
    {
        return $this->belongsTo(Monstruo::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}