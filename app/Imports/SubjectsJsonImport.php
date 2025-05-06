<?php

namespace App\Imports;

use App\Models\Subject;
use App\Models\SubjectGroup;
use Illuminate\Support\Facades\File;

class SubjectsJsonImport
{
    public function import()
    {
        // Leer el archivo JSON
        $jsonPath = base_path('materias.json');
        $subjects = json_decode(File::get($jsonPath), true);

        foreach ($subjects as $subject) {
            // Buscar o crear el grupo de materias (subcategoría)
            $subjectGroup = SubjectGroup::firstOrCreate(
                ['name' => $subject['Subcategoría']]
            );

            // Crear la materia
            Subject::create([
                'name' => $subject['Materia'],
                'subject_group_id' => $subjectGroup->id,
            ]);
        }
    }
} 