<?php

namespace App\Services;

use App\Models\Country;
use App\Models\Language;
use App\Models\Menu;
use App\Models\Rating;
use App\Models\CountryState;
use App\Models\MenuItem;
use App\Models\User;
use App\Models\UserSubject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\DB;

class SiteService {

// Consulta principal para obtener tutores
public function getTutors($data = array()) {
    try {
        // Selecciona todos los usuarios que tengan el rol 'tutor'
        $tutors = User::select('users.*')
            ->whereHas('roles', fn($query) => $query->whereName('tutor'));

         //dd($instructors->toSql());
        // Carga relaciones necesarias para mostrar información del tutor
        $tutors->with([
            'subjects', // Materias que enseña
            'languages:id,name', // Idiomas
            'address.country', // País de la dirección
            'profile', // Perfil del usuario
            'userSubjectSlots' // Slots de materiasp
        ]);

        // Solo tutores con perfil verificado y selecciona campos específicos del perfil
        $tutors->withWhereHas('profile', function ($query) {
            $query->select('id', 'verified_at', 'user_id', 'first_name', 'last_name', 'image', 'gender', 'tagline', 'description', 'slug', 'intro_video');
            $query->whereNotNull('verified_at'); // Solo tutores verificados
        });

          

        // Agrega promedios y conteos de reviews y estudiantes activos
        $tutors->withAvg('reviews as avg_rating', 'rating')
            ->withCount('reviews as total_reviews')
            ->withCount(['bookingSlots as active_students' => function($query){
                $query->whereStatus('active');
            }]);

        // Filtro por grupo de materias o por materias específicas
        if (!empty($data['subject_id'])) {
            // Si hay materias seleccionadas, filtra solo por esas materias
            $tutors->whereHas('subjects', function ($query) use ($data) {
                $subjectIds = is_array($data['subject_id']) ? $data['subject_id'] : [$data['subject_id']];
                $query->whereIn('subjects.id', $subjectIds);
            });
        } elseif (!empty($data['group_id'])) {
            // Si no hay materias pero sí grupo, filtra por grupo
            $tutors->whereHas('userSubjects.subject.group', function ($query) use ($data) {
                $query->where('id', $data['group_id']);
            });
        }

        // Filtro por palabra clave en nombre o apellido
        if (!empty($data['keyword'])) {
            $keyword = '%' . $data['keyword'] . '%';
            $tutors->where(function($query) use ($keyword) {
                $query->whereHas('profile', function ($q) use ($keyword) {
                    $q->where('first_name', 'like', $keyword)
                      ->orWhere('last_name', 'like', $keyword);
                });
            });
        }
        // Ordena por fecha de creación descendente (más nuevos primero)
        $tutors->orderBy('users.created_at', 'desc');

        // Log para debug: muestra la consulta SQL generada y los filtros recibidos
        \Log::info('SQL Query:', [
            'query' => $tutors->toSql(),
            'bindings' => $tutors->getBindings(),
            'data' => $data
        ]);

      
        // Paginación, por defecto 10 por página
        $result = $tutors->paginate(!empty($data['per_page']) ? $data['per_page'] : 10);
        
        // Log para debug: muestra el total de resultados y la página actual
        \Log::info('Query results:', [
            'total' => $result->total(),
            'current_page' => $result->currentPage(),
            'per_page' => $result->perPage()
        ]);

        return $result;

    } catch (\Exception $e) {
        // Log de error si algo falla
       
        \Log::error('Error in getTutors:', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return collect([]);
    }
}


    public function getRecommendedTutors($filters = [])
    {
        $tutors = User::select('id')->role('tutor');
        
        $tutors->with(['address' => function ($query) {
            $query->select('id','addressable_id','addressable_type','country_id')
                  ->with(['country' => function ($countryQuery) {
                      $countryQuery->select('id', 'name', 'short_code');
            }]);
        }]);

        $tutors->withAvg('reviews as avg_rating', 'rating')
        ->withCount('reviews as total_reviews')
        ->withCount(['bookingSlots as active_students' => function($query){
            $query->whereStatus('active');
        }]);
        $tutors->withWhereHas('profile', function ($query) {
            $query->whereNotNull('verified_at');
            $query->whereNotNull('intro_video');
            $query->select('id', 'user_id', 'first_name', 'last_name', 'image','slug','verified_at');
        });

        if(!empty($filters['order_by']) && $filters['order_by'] == 'ratings'){
            $tutors->orderBy('avg_rating', 'desc');
        }

        return $tutors->get()->take(!empty($filters['total']) ? $filters['total'] : 10);
    }


    public function getUserRole($slug) {
        return User::whereHas('profile', function ($query) use ($slug) {
            $query->whereSlug($slug);
        })->firstOrFail()->roles->pluck('name')->first();
    }

    public function getTutorDetail($slug): User|null {

        $isNotAdmin  = !auth()?->user()?->hasRole('admin') ?? true;
        return User::with([
            'languages:id,name',
            'userSubjects.subject',
        ])
        ->when(\Nwidart\Modules\Facades\Module::has('starup') && \Nwidart\Modules\Facades\Module::isEnabled('starup'), function ($query) {
            $query->with('badges:id,name,image');
        })
        ->with(['address' => function ($query) {
            $query->select('id','addressable_id','addressable_type','country_id')
                  ->with(['country' => function ($countryQuery) {
                      $countryQuery->select('id', 'name', 'short_code');
            }]);
        }])
        ->withWhereHas('profile', function ($query) use ($slug,$isNotAdmin) {
            if ($isNotAdmin) {
                $query->whereNotNull('verified_at');
            }
            $query->whereSlug($slug);
            $query->select('id', 'user_id', 'verified_at', 'first_name', 'tagline', 'keywords', 'last_name', 'slug', 'image', 'intro_video', 'description');
        })
        ->withAvg('reviews as avg_rating', 'rating')
        ->withCount('reviews as total_reviews')
        ->withCount(['bookingSlots as active_students' => function($query){
            $query->whereStatus('active');
        }])
        ->with('socialProfiles')
        ->first();
    }


    public function getStudentProfile($slug): User {
        return User::whereHas(
            'profile',
            function ($query) use ($slug) {
                $query->whereSlug($slug);
            }
        )->with('languages', 'contacts')->firstOrFail();
    }

    public function getRelatedInstructors($user) {
        return User::select('id')
            ->whereHas('groups', function ($query) use ($user) {
                $query->whereIn('subject_group_id', $user->groups->pluck('subject_group_id'));
            })->whereHas('subjects', function ($query) use ($user) {
                $query->whereIn('subject_id', $user->subjects->pluck('subject_id'));
            })->with(['contacts', 'profile' => function ($query) {
                $query->select('id', 'user_id', 'verified_at', 'feature_expired_at', 'first_name', 'last_name', 'slug', 'image');
            }])
            ->with('educations')
            ->withAvg('reviews as avg_rating', 'rating')
            ->withCount('reviews as total_reviews')
            ->where('id', '<>', $user->id)
            ->withCount(['bookingSlots as active_students' => function($query){
                $query->whereStatus('active');
            }])
            ->role('instructor')->limit(3)->get();
    }

    public function getUserReviews($userId) {
        return User::find($userId)->select('id')->with([
            'profile' => function ($query) {
                $query->select('id', 'user_id', 'verified_at', 'first_name', 'last_name');
            },
            'reviews'
        ])->get();
    }

    public function getCountries()
    {
        return Country::get(['id','name']);
    }

    public function getLanguages()
    {
        return  Language::get(['id', 'name']);
    }

    public function getStates()
    {
        return CountryState::get(['id', 'name']);
    }
    
    public function getState($id)
    {
        return CountryState::where('id',$id)->get(['id','name'])->first();
    }

    /**
     * Get site menu
     * @param void
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSiteMenu($location, $name = null) {
        $header_menu = [];
        $menu = Cache::rememberForever('menu-'.$location.'-'.$name, function() use($location, $name) {
            return Menu::select('id','name')
                    ->where('location', $location)
                    ->when(!empty($name), function ($query) use ($name) {
                        $query->where('name', $name);
                    })
                    ->latest()
                    ->first();
        });

        if( !empty($menu) ){
            $header_menu = Cache::rememberForever('menu-items-'.$menu->id, function() use($menu) {
                return MenuItem::where('menu_id', $menu->id)
                    ->orderBy('sort', 'asc')
                    ->tree()
                    ->get()
                    ->toTree();
            });
        }
        //dd($header_menu, "entonces por aca llega");   
        return $header_menu;
    }

    public function getMatchingInstructors($user){
        $userSubjects = $user?->subjects->pluck('subject_id')->toArray();
        $withRelations = [
            'address' => function ($query) {
                $query->with( 'country');
            },
            'languages',
            'educations',
            'subjects',
        ];

        if (Auth::check()) {
            $withRelations['favouriteByUsers'] = function ($query) {
                $query->where('user_id', Auth::user()?->id);
            };
        }

        return User::withWhereHas('profile', function ($query) {
            $query->whereNotNull('verified_at');
        })
        ->with($withRelations)
        // ->withMin('subjects as min_price', 'hour_rate')
        ->whereHas('subjects', function ($query) use ($userSubjects) {
            $query->whereIn('subject_id', $userSubjects);
        })
        ->where('id', '!=', $user->id)
        ->withAvg('reviews as avg_rating', 'rating')
        ->withCount('reviews as total_reviews')
        ->get()->take(4);
    }


    /**
     * Summary of featuredTutors
     */
  public function featuredTutors(){
        $featuredTutors = User::query()
            ->select('id')
            ->whereHas('roles', function($q) {
                $q->where('name', 'tutor');
            })
            ->whereHas('profile', function ($query) {
                $query->whereNotNull('verified_at');
            })
            // Solo tutores con al menos un registro en companyCourseUsers
            ->whereHas('companyCourseUsers')
            ->with([
                'profile:id,user_id,slug,tagline,verified_at,first_name,last_name,image,intro_video,description',
                'address.state',
                'address.country',
                'educations',
                'subjects',
                'userSubjectSlots'
            ])
            ->withCount([
                'bookingSlots as active_students' => function($query){
                    $query->whereStatus('active');
                },
                'companyCourseUsers',
                'companyCourseUsers as completed_courses_count' => function($query){
                    $query->where('status', 'completed');
                }
            ])
            ->withAvg('reviews as avg_rating', 'rating')
            ->withCount('reviews as total_reviews')
            // Gabriel Alpiry Hurtado siempre primero
            ->orderByRaw(
                "CASE WHEN EXISTS (\n                SELECT 1 FROM profiles p WHERE p.user_id = users.id AND p.first_name = ? AND p.last_name = ?\n            ) THEN 0 ELSE 1 END",
                ['Gabriel', 'Alpiry Hurtado']
            )
            // Luego los que tienen todos sus cursos en completed
            ->orderByRaw('CASE WHEN company_course_users_count > 0 AND completed_courses_count = company_course_users_count THEN 0 ELSE 1 END')
            // Luego por la cantidad de cursos completados
            ->orderByDesc('completed_courses_count')
            // Luego por la cantidad total de companyCourseUsers
            ->orderByDesc('company_course_users_count')
            ->inRandomOrder()
            ->get();
        return $featuredTutors;
    }

    public function clientsFeedback(){
        $user = Auth::user();
        $allRatings = range(1, 5);
        $allTutorRatings = Rating::with('profile')->get();
        return $allTutorRatings;
    }

    public function getTutorsWithSubjects()
    {
        // 1. Obtén los IDs de usuarios con rol 'tutor'
        $tutorRoleId = DB::table('roles')->where('name', 'tutor')->value('id');
        $tutorUserIds = DB::table('model_has_roles')
            ->where('role_id', $tutorRoleId)
            ->pluck('model_id');

        // 2. Extrae los perfiles de tutores
        $profiles = DB::table('profiles')
            ->whereIn('user_id', $tutorUserIds)
            ->select('user_id',
                DB::raw("SUBSTRING_INDEX(first_name, ' ', 1) as first_name"), //Extrae el primer nombre
                DB::raw("SUBSTRING_INDEX(last_name, ' ', 1) as last_name"), //Extrae el primer apellido
                'image', 'intro_video', 'native_language')
            ->get();

        // 3. Filtra los UserSubject solo para esos tutores
        $userSubjects = UserSubject::with(['subject.group'])
            ->whereIn('user_id', $tutorUserIds)
            ->get();

        // 4. Agrupa por user_id
        $subjectsByUser = $userSubjects->groupBy('user_id')->map(function($items) {
            return [
                'materias' => $items->pluck('subject.name')->unique()->values()->all(),
                'grupos'   => $items->pluck('subject.group.name')->unique()->values()->all(),
            ];
        });

        return [
            'profiles' => $profiles,
            'subjectsByUser' => $subjectsByUser
        ];
    }

    public function getAlliances()
    {
        return DB::table('alianzas')->get();
    }
}
