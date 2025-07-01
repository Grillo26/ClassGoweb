<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    protected $fillable = [
        'code',
        'state',
        'expiration_date',
        'user_id',
    ];
    protected $casts = [
        'state' => 'boolean',
        'expiration_date' => 'date',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
            
}
