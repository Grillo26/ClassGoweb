<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Models\User;

use Illuminate\Http\Request;

class ExportImageController extends Controller
{
    public function exportFicha($slug, $id){
        $user = \App\Models\User::findOrFail($id);
        $img = \Intervention\Image\Facades\Image::make(public_path('images/ficha_base.jpeg'));

        // Ejemplo de datos, puedes agregar más
        $img->text('Nombre: ' . $user->name, 100, 150, function ($font) {
            $font->file(public_path('fonts/OpenSans-Regular.ttf'));
            $font->size(36);
            $font->color('#000000');
            $font->align('left');
        });
        $img->text('Correo: ' . $user->email, 100, 200, function ($font) {
            $font->file(public_path('fonts/OpenSans-Regular.ttf'));
            $font->size(28);
            $font->color('#000000');
            $font->align('left');
        });

        // Guarda temporalmente
        $filename = 'ficha_' . $user->id . '.jpg';
        $img->save(public_path('fichas/' . $filename));

        // Para previsualización
        return response()->file(public_path('fichas/' . $filename));
    }
    public function downloadFicha($slug, $id)
    {
        $filename = 'ficha_' . $id . '.jpg';
        $path = public_path('fichas/' . $filename);
        if (!file_exists($path)) {
            // Genera la imagen si no existe
            $this->exportFicha($slug, $id);
        }
        return response()->download($path);
    }

    public function index($slug, $id){
        return view('vistas.view.pages.ficha', compact('slug', 'id'));
    }
}
