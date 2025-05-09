<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\SubjectGroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use App\Imports\SubjectsJsonImport;
use Illuminate\Support\Facades\DB;

class GeneralSeeder extends Seeder
{
    public function run()
    {
        // Crear usuario administrador
        $admin = User::firstOrCreate(['email' => 'admin@classgo.com'], [
            'email' => 'admin@classgo.com',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now()
        ]);
        
        $admin->profile()->create([
            'first_name' => 'Admin',
            'last_name' => 'ClassGo',
            'slug' => 'admin-classgo',
            'verified_at' => now()
        ]);

        $admin->assignRole('admin');

        // Limpiar tablas
        SubjectGroup::truncate();
        Subject::truncate();

        // Importar datos desde JSON
        $importer = new SubjectsJsonImport();
        $importer->import();

        // Crear archivos de placeholder
        Storage::disk(getStorageDisk())->putFileAs('', public_path('demo-content/placeholders/placeholder.png'), 'placeholder.png');
        Storage::disk(getStorageDisk())->putFileAs('', public_path('demo-content/placeholders/placeholder-land.png'), 'placeholder-land.png');

        // Cargar pagebuilder.json
        if (file_exists(base_path('pagebuilder.json'))) {
            $pagebuilderData = json_decode(file_get_contents(base_path('pagebuilder.json')), true);
            foreach ($pagebuilderData as $item) {
                DB::table('pagebuilder__pages')->updateOrInsert(
                    [
                        'id' => $item['id'] ?? null,
                    ],
                    [
                        'name' => $item['name'] ?? null,
                        'title' => $item['title'] ?? null,
                        'description' => $item['description'] ?? null,
                        'slug' => $item['slug'] ?? null,
                        'settings' => isset($item['settings']) ? json_encode($item['settings']) : null,
                        'status' => $item['status'] ?? 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}

