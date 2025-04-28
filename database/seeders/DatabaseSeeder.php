<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Schema;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        
        $this->call([
            CountrySeeder::class,
            CountryStatesSeeder::class,
            LanguageSeeder::class,
            RolePermissionsSeeder::class,
            DefaultSettingSeeder::class,
            GeneralSeeder::class,
            CustomTutorSeeder::class,
            CustomStudentSeeder::class,
        ]);
        
        Schema::enableForeignKeyConstraints();
    }
}
