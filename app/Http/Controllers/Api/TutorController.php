<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use App\Http\Resources\RecommendedTutor\RecommendedTutorResource;
use App\Http\Resources\FindTutors\TutorCollection;
use App\Http\Resources\TutorDetail\TutorDetailResource;
use App\Http\Resources\TutorSlots\TutorSlotResource;
use Carbon\Carbon;
use App\Models\Profile;
use App\Models\User;
use App\Models\UserSubject;
use App\Models\Subject;
use App\Services\BookingService;
use App\Services\ProfileService;
use App\Services\SiteService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TutorController extends Controller
{
    use ApiResponser;

    public function __construct()
    {
        $token = request()->bearerToken();
        $sanctumToken = PersonalAccessToken::findToken($token) ?? null;

        if (!empty($sanctumToken) && $sanctumToken->expires_at && Carbon::parse($sanctumToken->expires_at)->isFuture()) {
            $this->middleware('auth:sanctum');
        }
    }

    public function getRecommendedTutors()
    {
        $recommendedTutors  = (new SiteService)->getRecommendedTutors(['order_by' => 'ratings', 'total' => 10]);
        $tutors             =  $this->getFavouriateTutors($recommendedTutors);
        return $this->success(data: RecommendedTutorResource::collection($tutors));
    }

    public function findTutots(Request $request)
    {
        try {
            // Log de los parámetros recibidos
            Log::info('Parámetros de búsqueda:', [
                'keyword' => $request->keyword,
                'tutor_name' => $request->tutor_name,
                'group_id' => $request->group_id,
                'min_courses' => $request->min_courses,
                'min_rating' => $request->min_rating,
                'page' => $request->page
            ]);

            // Consulta base
            $query = User::whereHas('roles', function($q) {
                $q->where('name', 'tutor');
            })->with(['profile', 'subjects'])
              ->whereHas('profile', function($q) {
                  $q->whereNotNull('verified_at');
              });

            // Filtro por keyword (búsqueda en nombre de materia)
            if ($request->filled('keyword')) {
                $keyword = trim($request->keyword);
                $query->whereHas('subjects', function($q) use ($keyword) {
                    $q->where('name', 'LIKE', "%{$keyword}%");
                });
            }

            // Filtro por tutor_name (búsqueda en nombre del tutor)
            if ($request->filled('tutor_name')) {
                $tutorName = trim($request->tutor_name);
                $query->whereHas('profile', function($q) use ($tutorName) {
                    $q->where(function($subQ) use ($tutorName) {
                        $subQ->where('first_name', 'LIKE', "%{$tutorName}%")
                             ->orWhere('last_name', 'LIKE', "%{$tutorName}%")
                             ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', "%{$tutorName}%");
                    });
                });
            }

            // Filtro por group_id (categoría de materia)
            if ($request->filled('group_id')) {
                $query->whereHas('subjects', function($q) use ($request) {
                    $q->where('subject_group_id', $request->group_id);
                });
            }

            // Filtro por min_courses (número mínimo de cursos completados)
            if ($request->filled('min_courses')) {
                $minCourses = (int) $request->min_courses;
                $query->whereHas('companyCourseUsers', function($q) use ($minCourses) {
                    $q->where('status', 'completed');
                }, '>=', $minCourses);
            }

            // Filtro por min_rating (calificación mínima)
            if ($request->filled('min_rating')) {
                $minRating = (float) $request->min_rating;
                // Solo aplicar filtro si min_rating es mayor que 0
                if ($minRating > 0) {
                    $query->whereHas('reviews', function($q) use ($minRating) {
                        $q->select('tutor_id')
                          ->groupBy('tutor_id')
                          ->havingRaw('AVG(rating) >= ?', [$minRating]);
                    });
                }
            }

            // Ordenar por el nombre del tutor (usando el perfil relacionado)
            $query->join('profiles', 'users.id', '=', 'profiles.user_id')
                  ->orderBy('profiles.first_name', 'asc')
                  ->select('users.*');

            // Log del conteo de resultados
            $count = $query->count();
            Log::info('Número de tutores encontrados: ' . $count);

            // Paginación
            $perPage = 10; // Puedes hacer esto configurable
            $page = $request->filled('page') ? (int) $request->page : 1;
            
            $tutors = $query->paginate($perPage, ['*'], 'page', $page);
            
            $tutors->getCollection()->transform(function ($tutor) {
                $tutor = $this->getFavouriateTutors($tutor);
                // Agregar el conteo de cursos completados
                $tutor->completed_courses_count = $tutor->getCompletedCoursesCount();
                return $tutor;
            });

            return $this->success(data: new TutorCollection($tutors));

        } catch (\Exception $e) {
            Log::error('Error en findTutots: ' . $e->getMessage());
            return $this->error(message: 'Error al buscar tutores: ' . $e->getMessage());
        }
    }

    public function getTutorDetail($slug)
    {
        $profile = Profile::whereSlug($slug)->first();
 
        if(!$profile){
            return $this->error(message: 'Tutor not found.',code: Response::HTTP_NOT_FOUND);
        }

        $tutor   = (new SiteService)->getTutorDetail($slug);

        if (!$tutor) {
            return $this->error(message: 'Tutor profile not verified.',code: Response::HTTP_UNAUTHORIZED);
        }

        $tutor      = $this->getFavouriateTutors($tutor);
        return $this->success(data: new TutorDetailResource($tutor));
    }

    public function getTutorAvailableSlots(Request $request)
    {
        $userId         = $request->user_id;
        $userTimeZone   = $request->user_time_zone;
        $filter         = $request->filter ?? [];
        $type           = $request->type;

        if (!empty($request->start_date) && !empty($request->end_date)) {
            $startDate  = Carbon::parse($request->start_date)->startOfWeek(Carbon::SUNDAY)->format('Y-m-d');
            $endDate    = Carbon::parse($request->start_date)->endOfWeek(Carbon::SATURDAY)->format('Y-m-d');
        }

        else {
            $startDate  =   Carbon::now()->startOfWeek(Carbon::SUNDAY)->format('Y-m-d');
            $endDate    =   Carbon::now()->endOfWeek(Carbon::SATURDAY)->format('Y-m-d');
        }

        if ($type == 'prev') {
            $startDate = Carbon::parse($startDate)->subDays(7)->format('Y-m-d');
            $endDate = Carbon::parse($endDate)->subDays(7)->format('Y-m-d');
        } elseif ($type == 'next') {
            $startDate = Carbon::parse($startDate)->addDays(7)->format('Y-m-d');
            $endDate = Carbon::parse($endDate)->addDays(7)->format('Y-m-d');
        }

        $dateRange = [
            'start_date'    => $startDate." 00:00:00",
            'end_date'      => $endDate." 23:59:59"
        ];

        if (empty($userId)) {
            return $this->error(data: null,message: 'Invalid parameters.',code: Response::HTTP_BAD_REQUEST);
        }

        $tutor = User::where('id', $userId)->first();

        if (!$tutor) {
            return $this->error(data: null,message: 'Tutor not found.',code: Response::HTTP_NOT_FOUND);
        }

        if ($tutor->role !== 'tutor') {
            return $this->error(data: null,message: 'Unauthorized access.',code: Response::HTTP_FORBIDDEN);
        }

        $bookingService = new BookingService();
        $availableSlots = $bookingService->getTutorAvailableSlots($userId, $userTimeZone, $dateRange, $filter);
        $userSlot = [
            'start_date'    => $startDate." 00:00:00",
            'end_date'      => $endDate." 23:59:59"
        ];

        foreach ($availableSlots as $date => $slots) {
            $formattedDate = Carbon::parse($date)->format('d M Y');
            $userSlot[$formattedDate] = TutorSlotResource::collection($slots);
        }

        return $this->success(data: $userSlot);
    }

    public function slotDetail($id)
    {
        $booking = \App\Models\SlotBooking::with(['tutor', 'slot', 'subject'])->find($id);
        if (!$booking) {
            return $this->error(data: null, message: __('api.booking_not_found'), code: 404);
        }
        return $this->success(data: new \App\Http\Resources\SlotBookingResource($booking));
    }

    public function getFavouriateTutors($tutors)
    {
        $favoritesTutor = [];
        if (Auth::check()) {
            $user           = Auth::user();
            $userService    = new UserService($user);
            $favoritesTutor = $userService->getFavouriteUsers()->get(['favourite_user_id'])?->pluck('favourite_user_id')->toArray();
        }

        if (is_array($tutors) || $tutors instanceof \Illuminate\Support\Collection) {
            $usersWithFavorites = $tutors->map(function ($user) use ($favoritesTutor) {
            $user->is_favorite  = in_array($user->id, $favoritesTutor);
            return $user;
        });
        } else {
            $user                   = $tutors;
            $user->is_favorite      = in_array($user->id, $favoritesTutor);
            $usersWithFavorites     = $user;
        }
        return $usersWithFavorites;
    }

    public function getStates(Request $request)
    {
        $countryId = $request?->country_id;
        $profileService = new ProfileService();
        $states = $profileService->countryStates($countryId);
        if($states->isEmpty()){
            return $this->error(data: null,message: __('api.no_states_found'),code: Response::HTTP_NOT_FOUND);
        }else{
            return $this->success(data: $states,message: __('api.states_fetched_successfully'));
        }
    }

    /**
     * API: Obtener tutores verificados con materias registradas y filtros estrictos
     * GET /api/verified-tutors
     */
    public function getVerifiedTutorsWithSubjects(Request $request)
    {
        try {
            // Log de los parámetros recibidos
            Log::info('Parámetros de búsqueda verified-tutors:', [
                'keyword' => $request->keyword,
                'tutor_name' => $request->tutor_name,
                'group_id' => $request->group_id,
                'subject_id' => $request->subject_id,
                'min_courses' => $request->min_courses,
                'min_rating' => $request->min_rating,
                'page' => $request->page
            ]);

            // Consulta base - Solo tutores verificados con materias registradas
            $query = User::whereHas('roles', function($q) {
                $q->where('name', 'tutor');
            })->with(['profile', 'subjects'])
              ->whereHas('profile', function($q) {
                  $q->whereNotNull('verified_at');
              })
              ->whereHas('subjects'); // Solo tutores con materias registradas

            // Filtro por keyword (búsqueda en nombre de materia)
            if ($request->filled('keyword')) {
                $keyword = trim($request->keyword);
                $query->whereHas('subjects', function($q) use ($keyword) {
                    $q->where('name', 'LIKE', "%{$keyword}%");
                });
            }

            // Filtro por tutor_name (búsqueda en nombre del tutor)
            if ($request->filled('tutor_name')) {
                $tutorName = trim($request->tutor_name);
                $query->whereHas('profile', function($q) use ($tutorName) {
                    $q->where(function($subQ) use ($tutorName) {
                        $subQ->where('first_name', 'LIKE', "%{$tutorName}%")
                             ->orWhere('last_name', 'LIKE', "%{$tutorName}%")
                             ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', "%{$tutorName}%");
                    });
                });
            }

            // Filtro por group_id (categoría de materia)
            if ($request->filled('group_id')) {
                $query->whereHas('subjects', function($q) use ($request) {
                    $q->where('subject_group_id', $request->group_id);
                });
            }

            // Filtro por subject_id (materia específica)
            if ($request->filled('subject_id')) {
                $query->whereHas('subjects', function($q) use ($request) {
                    $q->where('subjects.id', $request->subject_id);
                });
            }

            // Filtro por min_courses (número mínimo de cursos completados)
            if ($request->filled('min_courses')) {
                $minCourses = (int) $request->min_courses;
                $query->whereHas('companyCourseUsers', function($q) use ($minCourses) {
                    $q->where('status', 'completed');
                }, '>=', $minCourses);
            }

            // Filtro por min_rating (calificación mínima)
            if ($request->filled('min_rating')) {
                $minRating = (float) $request->min_rating;
                // Solo aplicar filtro si min_rating es mayor que 0
                if ($minRating > 0) {
                    $query->whereHas('reviews', function($q) use ($minRating) {
                        $q->select('tutor_id')
                          ->groupBy('tutor_id')
                          ->havingRaw('AVG(rating) >= ?', [$minRating]);
                    });
                }
            }

            // Ordenar por el nombre del tutor (usando el perfil relacionado)
            $query->join('profiles', 'users.id', '=', 'profiles.user_id')
                  ->orderBy('profiles.first_name', 'asc')
                  ->select('users.*');

            // Log del conteo de resultados
            $count = $query->count();
            Log::info('Número de tutores verificados encontrados: ' . $count);

            // Paginación
            $perPage = 10; // Puedes hacer esto configurable
            $page = $request->filled('page') ? (int) $request->page : 1;
            
            $tutors = $query->paginate($perPage, ['*'], 'page', $page);
            
            $tutors->getCollection()->transform(function ($tutor) {
                $tutor = $this->getFavouriateTutors($tutor);
                // Agregar el conteo de cursos completados
                $tutor->completed_courses_count = $tutor->getCompletedCoursesCount();
                return $tutor;
            });

            return $this->success(data: new \App\Http\Resources\FindTutors\TutorCollection($tutors));

        } catch (\Exception $e) {
            Log::error('Error en getVerifiedTutorsWithSubjects: ' . $e->getMessage());
            return $this->error(message: 'Error al buscar tutores verificados: ' . $e->getMessage());
        }
    }

    /**
     * API: Obtener la ruta de la foto de perfil de los tutores verificados
     * GET /api/verified-tutors-photos
     */
    public function getVerifiedTutorsPhotos(Request $request)
    {
        try {
            $tutors = \App\Models\User::whereHas('roles', function($q) {
                    $q->where('name', 'tutor');
                })
                ->whereHas('profile', function($q) {
                    $q->whereNotNull('verified_at');
                })
                ->with(['profile' => function($q) {
                    $q->select('id', 'user_id', 'image');
                }])
                ->get();

            $result = $tutors->map(function($tutor) {
                $rutaBD = $tutor->profile ? $tutor->profile->image : null;
                $url = $rutaBD ? url('public/storage/' . $rutaBD) : null;
                return [
                    'id' => $tutor->id,
                    'profile_image' => $url,
                    'profile_image_db_path' => $rutaBD
                ];
            });

            return $this->success($result, 'Fotos de perfil de tutores verificados obtenidas exitosamente');
        } catch (\Exception $e) {
            \Log::error('Error en getVerifiedTutorsPhotos: ' . $e->getMessage());
            return $this->error(message: 'Error al obtener fotos de tutores verificados: ' . $e->getMessage());
        }
    }
}
