<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            'English' => 'en',
            'Spanish' => 'es',
            'French' => 'fr',
            'German' => 'de',
            'Italian' => 'it',
            'Portuguese' => 'pt',
            'Russian' => 'ru',
            'Chinese' => 'zh',
            'Japanese' => 'ja',
            'Korean' => 'ko',
            'Arabic' => 'ar',
        ];

        foreach ($languages as $name => $code) {
            DB::table('languages')->where('name', $name)->update(['code' => $code]);
        }
    }
}