<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use App\Models\UserSubject;
use App\Services\SiteService;


use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $tutorService;

    public function __construct(SiteService $tutorService)
    {
        $this->tutorService = $tutorService;
    }

    public function index(){
        
       // Obtener tutores y materias
        $tutorData = $this->tutorService->getTutorsWithSubjects();

        // Obtener alianzas
        $alianzas = $this->tutorService->getAlliances();

        return view('vistas.view.pages.home', [
            'profiles' => $tutorData['profiles'],
            'subjectsByUser' => $tutorData['subjectsByUser'],
            'alianzas' => $alianzas
        ]);
    }

    public function nosotros() {
    // Obtener alianzas
    $alianzas = $this->tutorService->getAlliances();

    return view('vistas.view.pages.nosotros', [
        'alianzas' => $alianzas
    ]);
    }
    public function tutor($slug){
        $tutor = $this->tutorService->getTutorDetail($slug);
        if (!$tutor) {
            abort(404, 'Tutor no encontrado');
        }
        // Extraer materias y grupos
        $materias = [];
        $grupos = [];
        if ($tutor->userSubjects) {
            foreach ($tutor->userSubjects as $userSubject) {
                if ($userSubject->subject) {
                    $materias[] = $userSubject->subject->name;
                    if ($userSubject->subject->group) {
                        $grupos[] = $userSubject->subject->group->name;
                    }
                }
            }
        }
        $materias = array_unique($materias);
        $grupos = array_unique($grupos);
        return view('vistas.view.pages.tutor', [
            'tutor' => $tutor,
            'materias' => $materias,
            'grupos' => $grupos
        ]);
    }
}
