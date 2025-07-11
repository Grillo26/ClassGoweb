<?php

namespace App\Services;


use App\Models\Code;
use App\Models\Coupon;
use App\Models\UserCoupon;
use Illuminate\Support\Str;
use App\Services\interfaces\ICuponesService;


class CuponesService implements ICuponesService
{


    function codeFriendly($code, $user)
    {
        if ($code->user_id) {
            UserCoupon::create([
                'coupon_id' => Coupon::create([
                    'code_id' => $code->id,
                    'descuento' => 100, // Descuento del 100%
                    'fecha_caducidad' => now()->endOfMonth(), // Vence al final del siguiente mes
                    'estado' => 'activo',
                ])->id,
                'user_id' => $code->user_id,
                'cantidad' => 1,
            ]);
            if ($user) {
                UserCoupon::create([
                    'coupon_id' => Coupon::create([
                        'code_id' => $code->id,
                        'descuento' => 100, // Descuento del 100%
                        'fecha_caducidad' => now()->endOfMonth(), // Vence al final del siguiente mes
                        'estado' => 'activo',
                    ])->id,
                    'user_id' => $user->id,
                    'cantidad' => 1,
                ]);
            }
        }
    }


    function codeCoupons($user)
    {
        UserCoupon::create([
            'coupon_id' => Coupon::create([
                'code_id' =>
                    Code::create([
                        'nombre' => 'Código de bienvenida',
                        'codigo' => Str::random(8), // Generar un código único
                        'user_id' => $user->id,
                        'fecha_caducidad' => null,
                        'descuento' => 100, // Descuento del 100%
                    ])->id,
                'descuento' => 100, // Descuento del 100%
                'fecha_caducidad' => now()->endOfMonth(), // Vence al final del siguiente mes
                'estado' => 'inactivo',
            ])->id,
            'user_id' => $user->id,
            'cantidad' => 5,
        ]);
    }

    function cupomcodigorandom($code, $user)
    {
        if ($code) {
            if (!$code->user_id) {
                // añade 5 a ti
                UserCoupon::create([
                    'coupon_id' => Coupon::create([
                        'code_id' => $code->id,
                        'descuento' => $code->descuento, // Descuento del 100%
                        'fecha_caducidad' => $code->fecha_caducidad, // Vence al final del siguiente mes
                        'estado' => 'activo',
                    ])->id,
                    'user_id' => $user->id,
                    'cantidad' => 1,
                ]);
            }
        }
    }
}


