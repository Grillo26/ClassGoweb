<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $languages = [
            ['code' => 'fr', 'name' => 'French'],
            ['code' => 'de', 'name' => 'German'],
            ['code' => 'it', 'name' => 'Italian'],
            ['code' => 'pt', 'name' => 'Portuguese'],
            ['code' => 'ru', 'name' => 'Russian'],
            ['code' => 'zh', 'name' => 'Chinese'],
            ['code' => 'ja', 'name' => 'Japanese'],
            ['code' => 'ko', 'name' => 'Korean']
        ];

        foreach ($languages as $lang) {
            // Verificar si el idioma ya existe
            $exists = DB::table('ltu_languages')
                ->where('code', $lang['code'])
                ->exists();

            if (!$exists) {
                // Insertar el idioma si no existe
                $language_id = DB::table('ltu_languages')->insertGetId([
                    'name' => $lang['name'],
                    'code' => $lang['code'],
                    'rtl' => false
                ]);

                // Crear la traducción para este idioma
                DB::table('ltu_translations')->insert([
                    'language_id' => $language_id,
                    'source' => false,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }

    public function down(): void
    {
        $codes = ['fr', 'de', 'it', 'pt', 'ru', 'zh', 'ja', 'ko'];
        
        $language_ids = DB::table('ltu_languages')
            ->whereIn('code', $codes)
            ->pluck('id');

        DB::table('ltu_translations')
            ->whereIn('language_id', $language_ids)
            ->delete();

        DB::table('ltu_languages')
            ->whereIn('code', $codes)
            ->delete();
    }
}; 