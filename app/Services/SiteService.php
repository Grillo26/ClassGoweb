<?php

namespace App\Services;

use App\Models\Country;
use App\Models\Language;
use App\Models\Menu;
use App\Models\Rating;
use App\Models\CountryState;
use App\Models\MenuItem;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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
                $query->whereIn('subjects.id', $data['subject_id']);
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

    public function featuredTutors(){
        $featuredTutors = User::select('id')->role('tutor')
                        ->withWhereHas(
                            'profile', function ($query) {
                                $query->select('id', 'user_id', 'slug', 'tagline', 'verified_at', 'first_name', 'last_name', 'image', 'intro_video', 'description');
                                $query->whereNotNull('verified_at');
                            })
                        ->with(['address' => function ($query) {
                                $query->with('state', 'country');
                            },
                            'educations',
                            'subjects',
                        ])->withCount(['bookingSlots as active_students' => function($query){
                            $query->whereStatus('active');
                        }])
                        ->withAvg('reviews as avg_rating', 'rating')
                        ->withCount('reviews as total_reviews')
                        // ->take(10)
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
}
