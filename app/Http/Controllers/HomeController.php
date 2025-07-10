<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use App\Models\UserSubject;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        // 1. Obtén los IDs de usuarios con rol 'tutor'
        $tutorRoleId = DB::table('roles')->where('name', 'tutor')->value('id');
        $tutorUserIds = DB::table('model_has_roles')
            ->where('role_id', $tutorRoleId)
            ->pluck('model_id');

        // 2. Extrae solo los perfiles de tutores
        $profiles = DB::table('profiles')
            ->whereIn('user_id', $tutorUserIds)
            ->select('user_id', 'first_name', 'last_name', 'image', 'intro_video', 'native_language')
            ->get();

        // 3. Filtra los UserSubject solo para tutores
        $userSubjects = UserSubject::with(['subject.group'])
            ->whereIn('user_id', $tutorUserIds)
            ->get();

        // 4. Agrupa por user_id y prepara los datos
        $subjectsByUser = $userSubjects->groupBy('user_id')->map(function($items) {
            return [
                'materias' => $items->pluck('subject.name')->unique()->values()->all(),
                'grupos'   => $items->pluck('subject.group.name')->unique()->values()->all(),
            ];
        });

        $alianzas = Db::table('alianzas')->get();

        return view('vistas.view.pages.home', compact('profiles', 'subjectsByUser', 'alianzas'));
    }
}
