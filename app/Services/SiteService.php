<?php

namespace App\Services;

use App\Models\Country;
use App\Models\Language;
use App\Models\Menu;
use App\Models\Rating;
use App\Models\Setting;
use App\Models\CountryState;
use App\Models\MenuItem;
use App\Models\SlotBooking;
use App\Models\User;
use App\Models\UserSubjectSlot;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SiteService {

public function getTutors($data = array()) {
    try {
        $instructors = User::select('users.*')
            ->whereHas('roles', fn($query) => $query->whereName('tutor'));

        $instructors->with(['subjects' => function ($query) {
            $query->withCount(['slots as sessions' => fn($query) => $query->where('end_time', '>=', now())]);
            $query->with('subject:id,name');
        }, 'languages:id,name', 'address.country', 'profile']);

        $instructors->withWhereHas('profile', function ($query) {
            $query->select('id', 'verified_at', 'user_id', 'first_name', 'last_name', 'image', 'gender', 'tagline', 'description', 'slug', 'intro_video');
        });

        // Agregar conteos y promedios básicos
        $instructors->withMin('subjects as min_price', 'hour_rate')
            ->withAvg('reviews as avg_rating', 'rating')
            ->withCount('reviews as total_reviews')
            ->withCount(['bookingSlots as active_students' => function($query){
                $query->whereStatus('active');
            }]);

        // Aplicar filtros solo si están presentes
        if (!empty($data['group_id'])) {
            $instructors->whereHas('groups', function ($query) use ($data) {
                $query->where('subject_group_id', $data['group_id']);
            });
        }

        if (!empty($data['keyword'])) {
            $keyword = '%' . $data['keyword'] . '%';
            $instructors->where(function($query) use ($keyword) {
                $query->whereHas('profile', function ($q) use ($keyword) {
                    $q->where('first_name', 'like', $keyword)
                      ->orWhere('last_name', 'like', $keyword);
                });
            });
        }

        // Ordenar por fecha de creación por defecto
        $instructors->orderBy('users.created_at', 'desc');

        \Log::info('SQL Query:', [
            'query' => $instructors->toSql(),
            'bindings' => $instructors->getBindings(),
            'data' => $data
        ]);

        $result = $instructors->paginate(!empty($data['per_page']) ? $data['per_page'] : 10);
        
        \Log::info('Query results:', [
            'total' => $result->total(),
            'current_page' => $result->currentPage(),
            'per_page' => $result->perPage()
        ]);

        return $result;

    } catch (\Exception $e) {
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
        $tutors->with(['subjects' => function ($query) {
            $query->withCount(['slots as sessions' => fn($query) => $query->where('end_time', '>=', now())]);
            $query->with('subject:id,name');
        }, 'languages:id,name']);

        $tutors->with(['address' => function ($query) {
            $query->select('id','addressable_id','addressable_type','country_id')
                  ->with(['country' => function ($countryQuery) {
                      $countryQuery->select('id', 'name', 'short_code');
            }]);
        }]);

        $tutors->withMin('subjects as min_price', 'hour_rate')
        ->withAvg('reviews as avg_rating', 'rating')
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

    // public function getActiveUsers($slug) {
    //     $slots = UserSubjectSlot::select('id','start_time','spaces','total_booked')
    //     ->whereHas('subjectGroupSubjects', function($groupSubjects) {
    //         $groupSubjects->select('id','user_subject_group_id');
    //         $groupSubjects->whereHas('userSubjectGroup', fn($query)=>$query->select('id','user_id')->whereUserId($this->user->id));
    //     })->get();
    // }

    public function getUserRole($slug) {
        return User::whereHas('profile', function ($query) use ($slug) {
            $query->whereSlug($slug);
        })->firstOrFail()->roles->pluck('name')->first();
    }

    public function getTutorDetail($slug): User|null {

        $isNotAdmin  = !auth()?->user()?->hasRole('admin') ?? true;
        return User::with([
            'languages:id,name',
            'subjects.subject:id,name',
        ])
        ->when(\Nwidart\Modules\Facades\Module::has('starup') && \Nwidart\Modules\Facades\Module::isEnabled('starup'), function ($query) {
            $query->with('badges:id,name,image');
        })
        ->with('subjects', function ($query) {
            $query->withCount(['slots as sessions' => fn($query) => $query->where('end_time', '>=', now())]);
        })
        ->with(['address' => function ($query) {
            $query->select('id','addressable_id','addressable_type','country_id')
                  ->with(['country' => function ($countryQuery) {
                      $countryQuery->select('id', 'name', 'short_code');
            }]);
        }])
        ->withMin('subjects as min_price', 'hour_rate')
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
            ->withMin('subjects as min_price', 'hour_rate')->withMax('subjects as max_price', 'hour_rate')->role('instructor')->limit(3)->get();
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
            'subjects.subject',
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
        ->withMin('subjects as min_price', 'hour_rate')
        ->whereHas('subjects', function ($query) use ($userSubjects) {
            $query->whereIn('subject_id', $userSubjects);
        })
        ->where('id', '!=', $user->id)
        ->withAvg('reviews as avg_rating', 'rating')
        ->withCount('reviews as total_reviews')
        ->withCount(['bookingSlots as active_students' => function($query) {
            $query->whereStatus('active')
            ->withCount(['slot as total_sessions']);
        }])
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
                            'subjects.subject',
                        ])->withCount(['bookingSlots as active_students' => function($query){
                            $query->whereStatus('active');
                        }])
                        ->withMin('subjects as min_price', 'hour_rate')
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
