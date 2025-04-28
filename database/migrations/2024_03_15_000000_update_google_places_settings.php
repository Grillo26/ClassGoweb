<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateGooglePlacesSettings extends Migration
{
    public function up()
    {
        // Actualizar o insertar la configuración de Google Places
        DB::table('optionbuilder__settings')->updateOrInsert(
            [
                'section' => '_api',
                'key' => 'enable_google_places'
            ],
            [
                'value' => env('ENABLE_GOOGLE_PLACES', '1')
            ]
        );

        DB::table('optionbuilder__settings')->updateOrInsert(
            [
                'section' => '_api',
                'key' => 'google_places_api_key'
            ],
            [
                'value' => env('GOOGLE_PLACES_API_KEY', '')
            ]
        );
    }

    public function down()
    {
        // Revertir los cambios si es necesario
        DB::table('optionbuilder__settings')
            ->where('section', '_api')
            ->whereIn('key', ['enable_google_places', 'google_places_api_key'])
            ->delete();
    }
} 