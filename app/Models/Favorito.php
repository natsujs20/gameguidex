<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Favorito extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'elemento_type',
        'elemento_id',
        'created_at',
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
