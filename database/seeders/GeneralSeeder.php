<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\SubjectGroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

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

        // Crear grupos de materias
        SubjectGroup::truncate();
        Subject::truncate();

        SubjectGroup::insert([
            ['name' => 'Primary school (Grade 1 to 5)'],
            ['name' => 'Middle school (Grades 6-8)'],
            ['name' => 'High school (Grades 9-10)'],
            ['name' => 'Intermediate (Grades 11-12)'],
            ['name' => "Undergraduate (Bachelor's Degree)"],
            ['name' => 'Graduate (Masters Degree)'],
            ['name' => 'Post graduate (Doctoral Degree)'],
        ]);

        // Obtener IDs de los grupos
        $primaryId = SubjectGroup::where('name', "Primary school (Grade 1 to 5)")->first()->id;
        $middleId = SubjectGroup::where('name', "Middle school (Grades 6-8)")->first()->id;
        $highId = SubjectGroup::where('name', "High school (Grades 9-10)")->first()->id;
        $intermediateId = SubjectGroup::where('name', "Intermediate (Grades 11-12)")->first()->id;
        $undergraduateId = SubjectGroup::where('name', "Undergraduate (Bachelor's Degree)")->first()->id;

        Subject::insert([
            // Materias de Primaria
            ['name' => 'Basic Mathematics', 'subject_group_id' => $primaryId],
            ['name' => 'English Reading & Writing', 'subject_group_id' => $primaryId],
            ['name' => 'Basic Science', 'subject_group_id' => $primaryId],
            ['name' => 'Social Studies', 'subject_group_id' => $primaryId],
            ['name' => 'Arts & Crafts', 'subject_group_id' => $primaryId],
            ['name' => 'Physical Education', 'subject_group_id' => $primaryId],

            // Materias de Secundaria
            ['name' => 'Pre-Algebra', 'subject_group_id' => $middleId],
            ['name' => 'Literature', 'subject_group_id' => $middleId],
            ['name' => 'Earth Science', 'subject_group_id' => $middleId],
            ['name' => 'World History', 'subject_group_id' => $middleId],
            ['name' => 'Basic Computing', 'subject_group_id' => $middleId],

            // Materias de High School
            ['name' => 'Algebra', 'subject_group_id' => $highId],
            ['name' => 'Geometry', 'subject_group_id' => $highId],
            ['name' => 'Biology', 'subject_group_id' => $highId],
            ['name' => 'Chemistry', 'subject_group_id' => $highId],
            ['name' => 'Physics', 'subject_group_id' => $highId],

            // Materias de Intermediate
            ['name' => 'Advanced Mathematics', 'subject_group_id' => $intermediateId],
            ['name' => 'Advanced Physics', 'subject_group_id' => $intermediateId],
            ['name' => 'Advanced Chemistry', 'subject_group_id' => $intermediateId],
            ['name' => 'Computer Science Basics', 'subject_group_id' => $intermediateId],

            // Materias de Universidad
            ['name' => 'Web Development', 'subject_group_id' => $undergraduateId],
            ['name' => 'Web Designing', 'subject_group_id' => $undergraduateId],
            ['name' => 'Software Development', 'subject_group_id' => $undergraduateId],
            ['name' => 'Database Management', 'subject_group_id' => $undergraduateId],
            ['name' => 'UI/UX Design', 'subject_group_id' => $undergraduateId],
            ['name' => 'Mobile App Development', 'subject_group_id' => $undergraduateId],
            ['name' => 'Network Security', 'subject_group_id' => $undergraduateId],
            ['name' => 'Artificial Intelligence', 'subject_group_id' => $undergraduateId]
        ]);

        // Crear archivos de placeholder
        Storage::disk(getStorageDisk())->putFileAs('', public_path('demo-content/placeholders/placeholder.png'), 'placeholder.png');
        Storage::disk(getStorageDisk())->putFileAs('', public_path('demo-content/placeholders/placeholder-land.png'), 'placeholder-land.png');
    }
}

