<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomStudentSeeder extends Seeder
{
    public function run(): void
    {
        $students = [
            [
                'email' => 'pedro.estudiante@example.com',
                'name' => 'Pedro Estudiante',
                'grade' => 'High School'
            ],
            [
                'email' => 'andrea.alumna@example.com',
                'name' => 'Andrea Alumna',
                'grade' => 'Middle School'
            ],
            [
                'email' => 'mario.escolar@example.com',
                'name' => 'Mario Escolar',
                'grade' => 'Primary School'
            ],
            [
                'email' => 'diana.aprendiz@example.com',
                'name' => 'Diana Aprendiz',
                'grade' => 'University'
            ],
            [
                'email' => 'luis.educando@example.com',
                'name' => 'Luis Educando',
                'grade' => 'High School'
            ],
            [
                'email' => 'carmen.estudiosa@example.com',
                'name' => 'Carmen Estudiosa',
                'grade' => 'Middle School'
            ],
            [
                'email' => 'roberto.alumno@example.com',
                'name' => 'Roberto Alumno',
                'grade' => 'University'
            ],
            [
                'email' => 'silvia.escolar@example.com',
                'name' => 'Silvia Escolar',
                'grade' => 'Primary School'
            ],
            [
                'email' => 'jorge.aprendiz@example.com',
                'name' => 'Jorge Aprendiz',
                'grade' => 'High School'
            ],
            [
                'email' => 'paula.educanda@example.com',
                'name' => 'Paula Educanda',
                'grade' => 'University'
            ]
        ];

        foreach ($students as $studentData) {
            // Crear usuario
            $user = User::create([
                'email' => $studentData['email'],
                'password' => Hash::make('password123'), // Contraseña común para todos los estudiantes
                'email_verified_at' => now(),
            ]);

            // Asignar rol de estudiante
            $user->assignRole('student');

            // Crear perfil
            $names = explode(' ', $studentData['name']);
            Profile::create([
                'user_id' => $user->id,
                'first_name' => $names[0],
                'last_name' => $names[1],
                'slug' => Str::slug($studentData['name']),
                'tagline' => $studentData['grade'] . ' Student',
                'description' => 'Student currently in ' . $studentData['grade'] . ' looking to improve academic performance.',
                'verified_at' => now(),
            ]);
        }
    }
} 