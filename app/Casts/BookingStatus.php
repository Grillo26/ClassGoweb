<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class BookingStatus implements CastsAttributes
{

    public static $statuses = [
        'Aceptado'      => 1,
        'Pendiente'     => 2,
        'No completado' => 3,
        'Rechazado'     => 4,
        'Completado'    => 5,
        'Cursando'      => 6,
    ];

    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $map = array_flip(self::$statuses);
        return $map[$value] ?? $value;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return self::$statuses[$value] ?? $value;
    }
}
