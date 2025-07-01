<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'fecha_caducidad',
        'estado',
    ];

   

    public function userCoupons()
    {
        return $this->hasMany(UserCoupon::class, 'coupon_id');
    }
}