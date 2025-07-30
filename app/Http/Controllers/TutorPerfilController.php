<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TutorPerfilController extends Controller
{
    public function show($id)
    {
        // Aquí se podría buscar el tutor por id en el futuro
        // Por ahora solo retorna la vista con el id
        return view('vistas.view.pages.tutores', [
            'tutor_id' => $id,
            // Aquí se pueden pasar más datos en el futuro
        ]);
    }
} 