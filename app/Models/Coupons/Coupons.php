<?php

namespace App\Models\Coupons;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Coupons extends Model
{
    protected $fillable = [
        'description',
        'state',
        'expiration_date',
        'discount_percentage',
    ];
    protected $casts = [
        'state' => 'boolean',
        'expiration_date' => 'date',
        'discount_percentage' => 'integer',
    ];
    /**
     * Relacion uno a muchos inversa con el modelo User
     */
    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('usage_limit')
            ->withTimestamps();
    }
}
