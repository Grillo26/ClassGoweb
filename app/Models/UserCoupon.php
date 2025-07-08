<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCoupon extends Model
{
    protected $fillable = [
        'coupon_id',
        'user_id',
        'cantidad',
    ];

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}