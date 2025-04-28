<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Profile;
use App\Models\UserEducation;
use App\Models\UserSubjectGroup;
use App\Models\UserSubjectGroupSubject;
use App\Models\Subject;
use App\Models\SubjectGroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomTutorSeeder extends Seeder
{
    public function run(): void
    {
        $tutors = [
            [
                'email' => 'juan.perez@example.com',
                'name' => 'Juan Pérez',
                'subjects' => ['Mathematics', 'Physics'],
                'group' => 'High school (Grades 9-10)'
            ],
            [
                'email' => 'maria.garcia@example.com',
                'name' => 'María García',
                'subjects' => ['Chemistry', 'Biology'],
                'group' => 'High school (Grades 9-10)'
            ],
            [
                'email' => 'carlos.rodriguez@example.com',
                'name' => 'Carlos Rodríguez',
                'subjects' => ['Web Development', 'Database Management'],
                'group' => "Undergraduate (Bachelor's Degree)"
            ],
            [
                'email' => 'ana.martinez@example.com',
                'name' => 'Ana Martínez',
                'subjects' => ['Literature', 'Social Studies'],
                'group' => 'Middle school (Grades 6-8)'
            ],
            [
                'email' => 'jose.lopez@example.com',
                'name' => 'José López',
                'subjects' => ['Advanced Mathematics', 'Advanced Physics'],
                'group' => 'Intermediate (Grades 11-12)'
            ],
            [
                'email' => 'laura.sanchez@example.com',
                'name' => 'Laura Sánchez',
                'subjects' => ['Basic Mathematics', 'English Reading & Writing'],
                'group' => 'Primary school (Grade 1 to 5)'
            ],
            [
                'email' => 'david.gonzalez@example.com',
                'name' => 'David González',
                'subjects' => ['Software Development', 'Mobile App Development'],
                'group' => "Undergraduate (Bachelor's Degree)"
            ],
            [
                'email' => 'sofia.torres@example.com',
                'name' => 'Sofía Torres',
                'subjects' => ['Pre-Algebra', 'Earth Science'],
                'group' => 'Middle school (Grades 6-8)'
            ],
            [
                'email' => 'miguel.ramirez@example.com',
                'name' => 'Miguel Ramírez',
                'subjects' => ['Advanced Chemistry', 'Computer Science Basics'],
                'group' => 'Intermediate (Grades 11-12)'
            ],
            [
                'email' => 'patricia.herrera@example.com',
                'name' => 'Patricia Herrera',
                'subjects' => ['Arts & Crafts', 'Physical Education'],
                'group' => 'Primary school (Grade 1 to 5)'
            ],
            [
                'email' => 'roberto.diaz@example.com',
                'name' => 'Roberto Díaz',
                'subjects' => ['UI/UX Design', 'Web Designing'],
                'group' => "Undergraduate (Bachelor's Degree)"
            ],
            [
                'email' => 'carmen.vargas@example.com',
                'name' => 'Carmen Vargas',
                'subjects' => ['Algebra', 'Geometry'],
                'group' => 'High school (Grades 9-10)'
            ],
            [
                'email' => 'francisco.morales@example.com',
                'name' => 'Francisco Morales',
                'subjects' => ['Network Security', 'Artificial Intelligence'],
                'group' => "Undergraduate (Bachelor's Degree)"
            ],
            [
                'email' => 'isabel.ortiz@example.com',
                'name' => 'Isabel Ortiz',
                'subjects' => ['Basic Science', 'Social Studies'],
                'group' => 'Primary school (Grade 1 to 5)'
            ],
            [
                'email' => 'alberto.castro@example.com',
                'name' => 'Alberto Castro',
                'subjects' => ['World History', 'Basic Computing'],
                'group' => 'Middle school (Grades 6-8)'
            ],
            [
                'email' => 'monica.ruiz@example.com',
                'name' => 'Mónica Ruiz',
                'subjects' => ['Biology', 'Chemistry'],
                'group' => 'High school (Grades 9-10)'
            ],
            [
                'email' => 'ricardo.silva@example.com',
                'name' => 'Ricardo Silva',
                'subjects' => ['Advanced Mathematics', 'Computer Science Basics'],
                'group' => 'Intermediate (Grades 11-12)'
            ],
            [
                'email' => 'elena.mendoza@example.com',
                'name' => 'Elena Mendoza',
                'subjects' => ['Web Development', 'UI/UX Design'],
                'group' => "Undergraduate (Bachelor's Degree)"
            ],
            [
                'email' => 'gabriel.flores@example.com',
                'name' => 'Gabriel Flores',
                'subjects' => ['Literature', 'Pre-Algebra'],
                'group' => 'Middle school (Grades 6-8)'
            ],
            [
                'email' => 'lucia.cruz@example.com',
                'name' => 'Lucía Cruz',
                'subjects' => ['Basic Mathematics', 'Arts & Crafts'],
                'group' => 'Primary school (Grade 1 to 5)'
            ],
        ];

        foreach ($tutors as $tutorData) {
            // Crear usuario
            $user = User::create([
                'email' => $tutorData['email'],
                'password' => Hash::make('password123'), // Contraseña común para todos los tutores
                'email_verified_at' => now(),
            ]);

            // Asignar rol de tutor
            $user->assignRole('tutor');

            // Crear perfil
            $names = explode(' ', $tutorData['name']);
            $profile = Profile::create([
                'user_id' => $user->id,
                'first_name' => $names[0],
                'last_name' => $names[1],
                'slug' => Str::slug($tutorData['name']),
                'tagline' => 'Professional ' . implode(' & ', $tutorData['subjects']) . ' Tutor',
                'description' => 'Experienced tutor specializing in ' . implode(' and ', $tutorData['subjects']) . '.',
                'verified_at' => now(),
            ]);

            // Obtener grupo y asignar materias
            $group = SubjectGroup::where('name', $tutorData['group'])->first();
            if ($group) {
                $userGroup = UserSubjectGroup::create([
                    'user_id' => $user->id,
                    'subject_group_id' => $group->id
                ]);

                foreach ($tutorData['subjects'] as $subjectName) {
                    $subject = Subject::where('name', $subjectName)->first();
                    if ($subject) {
                        UserSubjectGroupSubject::create([
                            'user_subject_group_id' => $userGroup->id,
                            'subject_id' => $subject->id,
                            'hour_rate' => rand(20, 50) // Tarifa aleatoria entre 20 y 50
                        ]);
                    }
                }
            }

            // Crear educación
            UserEducation::create([
                'user_id' => $user->id,
                'course_title' => 'Bachelor in ' . $tutorData['subjects'][0],
                'institute_name' => 'Universidad Example',
                'country_id' => 1,
                'city' => 'Ciudad Example',
                'start_date' => '2015-09-01',
                'end_date' => '2019-06-01',
                'ongoing' => 0,
                'description' => 'Specialized in ' . implode(' and ', $tutorData['subjects'])
            ]);
        }
    }
} 