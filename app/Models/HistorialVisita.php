<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class HistorialVisita extends Model
{
    protected $table = 'historial';

    protected $fillable = [
        'usuario_id',
        'elemento_type',
        'elemento_id',
        'visitado_en',
    ];

    protected $casts = [
        'visitado_en' => 'datetime',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function elemento(): MorphTo
    {
        return $this->morphTo();
    }
}
