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
}
