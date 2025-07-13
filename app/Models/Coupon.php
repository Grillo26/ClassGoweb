<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'fecha_caducidad',
        'estado',
        'descuento',
        'codigo',

    ];

   

    public function userCoupons()
    {
        return $this->hasMany(UserCoupon::class, 'coupon_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_coupons')->withPivot('cantidad')->withTimestamps();
    }

}