<?php

namespace App\Models;

use Carbon\Carbon;

class Day {

    protected static $rows = [
        [
            'id' => 1,
            'week_day' => Carbon::SUNDAY,
            'short_name' => 'Dom',
            'name' => 'Domingo'
        ],
        [
            'id' => 2,
            'week_day' => Carbon::MONDAY,
            'short_name' => 'Lun',
            'name' => 'Lunes'
        ],
        [
            'id' => 3,
            'week_day' => Carbon::TUESDAY,
            'short_name' => 'Mar',
            'name' => 'Martes'
        ],
        [
            'id' => 4,
            'week_day' => Carbon::WEDNESDAY,
            'short_name' => 'Mié',
            'name' => 'Miércoles'
        ],
        [
            'id' => 5,
            'week_day' => Carbon::THURSDAY,
            'short_name' => 'Jue',
            'name' => 'Jueves'
        ],
        [
            'id' => 6,
            'week_day' => Carbon::FRIDAY,
            'short_name' => 'Vie',
            'name' => 'Viernes'
        ],
        [
            'id' => 7,
            'week_day' => Carbon::SATURDAY,
            'short_name' => 'Sáb',
            'name' => 'Sábado'
        ]
    ];

    public static function get($startDay = Carbon::SUNDAY) {
        $startPosition = array_search($startDay, array_column(self::$rows, 'week_day'));
        return  array_merge(
            array_slice(self::$rows, $startPosition),
            array_slice(self::$rows, 0, $startPosition)
        );
    }
}
