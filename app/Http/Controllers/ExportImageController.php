<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

use Illuminate\Http\Request;

class ExportImageController extends Controller
{
    public function exportFicha($id){
        $user = User::findOrFail($id); // o Auth::user()

        // Cargar la imagen base desde /public/images/ficha_base.jpg
        $img = Image::make(public_path('images/ficha_base.jpg'));

        $img->text('Correo ' . $user->correo, 100, 200, function ($font) {
            $font->file(public_path('fonts/OpenSans-Regular.ttf')); 
            $font->size(36);
            $font->color('#000000');
            $font->align('left');
        });
        // Descargar directamente como JPG
        return $img->response('jpg'); // o 'png'
    }
}
