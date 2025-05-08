<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alianza extends Model
{
    protected $fillable = [
        'titulo',
        'imagen',
        'enlace',
        'activo',
        'orden'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'orden' => 'integer'
    ];

    public function getImagenUrlAttribute()
    {
        return asset('storage/' . $this->imagen);
    }
} 