<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    protected $fillable = [
        'nombre',
        'descuento',
        'codigo',
        'user_id',
        'fecha_caducidad',
    ];

    

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}