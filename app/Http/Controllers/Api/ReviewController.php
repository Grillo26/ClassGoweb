<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\UserReview;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ReviewController extends Controller
{
    use ApiResponser;

    /**
     * Obtener todas las reseñas que RECIBE un usuario
     */
    public function index(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'page' => 'integer|min:1',
                'per_page' => 'integer|min:1|max:50'
            ]);

            if ($validator->fails()) {
                return $this->error(
                    message: 'Datos de entrada inválidos',
                    data: $validator->errors(),
                    code: Response::HTTP_BAD_REQUEST
                );
            }

            $userId = $request->user_id;
            $perPage = $request->per_page ?? 10;
            $page = $request->page ?? 1;

            $userReviews = UserReview::where('user_id', $userId)
                ->whereHas('review', function($q) {
                    $q->where('status', 'active');
                })
                ->with([
                    'reviewer:id,name,email,created_at',
                    'reviewer.profile:id,user_id,first_name,last_name,avatar,phone,address,city,state,country,zip_code,bio',
                    'review:id,rating,comment,status,created_at,updated_at'
                ])
                ->orderBy('created_at', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);

            return $this->success(
                data: $userReviews,
                message: 'Reseñas recibidas obtenidas exitosamente'
            );

        } catch (\Exception $e) {
            Log::error('Error al obtener reseñas recibidas: ' . $e->getMessage());
            return $this->error(
                message: 'Error al obtener reseñas recibidas',
                code: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Obtener todas las reseñas que HIZO un usuario
     */
    public function getUserReviews(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'page' => 'integer|min:1',
                'per_page' => 'integer|min:1|max:50'
            ]);

            if ($validator->fails()) {
                return $this->error(
                    message: 'Datos de entrada inválidos',
                    data: $validator->errors(),
                    code: Response::HTTP_BAD_REQUEST
                );
            }

            $userId = $request->user_id;
            $perPage = $request->per_page ?? 10;
            $page = $request->page ?? 1;

            $userReviews = UserReview::where('reviewer_id', $userId)
                ->whereHas('review', function($q) {
                    $q->where('status', 'active');
                })
                ->with([
                    'user:id,name,email,created_at',
                    'user.profile:id,user_id,first_name,last_name,avatar,phone,address,city,state,country,zip_code,bio',
                    'review:id,rating,comment,status,created_at,updated_at'
                ])
                ->orderBy('created_at', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);

            return $this->success(
                data: $userReviews,
                message: 'Reseñas realizadas obtenidas exitosamente'
            );

        } catch (\Exception $e) {
            Log::error('Error al obtener reseñas realizadas: ' . $e->getMessage());
            return $this->error(
                message: 'Error al obtener reseñas realizadas',
                code: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Obtener todas las reseñas que RECIBE un usuario (alias del método index)
     */
    public function getReceivedReviews(Request $request)
    {
        return $this->index($request);
    }

    /**
     * Crear una nueva reseña
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'rating' => 'required|numeric|min:0|max:5',
                'comment' => 'nullable|string|max:1000'
            ]);

            if ($validator->fails()) {
                return $this->error(
                    message: 'Datos de entrada inválidos',
                    data: $validator->errors(),
                    code: Response::HTTP_BAD_REQUEST
                );
            }

            $reviewerId = Auth::id();
            $userId = $request->user_id;

            // Verificar que el usuario no se esté reseñando a sí mismo
            if ($reviewerId == $userId) {
                return $this->error(
                    message: 'No puedes reseñarte a ti mismo',
                    code: Response::HTTP_BAD_REQUEST
                );
            }

            // Verificar si ya existe una reseña
            if (UserReview::hasUserReviewed($reviewerId, $userId)) {
                return $this->error(
                    message: 'Ya has reseñado a este usuario',
                    code: Response::HTTP_CONFLICT
                );
            }

            // Crear la reseña
            $review = Review::create([
                'rating' => $request->rating,
                'comment' => $request->comment,
                'status' => 'active'
            ]);

            // Crear la relación en la tabla intermedia
            $userReview = UserReview::create([
                'user_id' => $userId,
                'reviewer_id' => $reviewerId,
                'review_id' => $review->id
            ]);

            $userReview->load([
                'reviewer:id,name,email,created_at',
                'reviewer.profile:id,user_id,first_name,last_name,avatar,phone,address,city,state,country,zip_code,bio',
                'user:id,name,email,created_at',
                'user.profile:id,user_id,first_name,last_name,avatar,phone,address,city,state,country,zip_code,bio',
                'review:id,rating,comment,status,created_at,updated_at'
            ]);

            Log::info('Nueva reseña creada', [
                'review_id' => $review->id,
                'user_review_id' => $userReview->id,
                'reviewer_id' => $reviewerId,
                'user_id' => $userId,
                'rating' => $request->rating
            ]);

            return $this->success(
                data: $userReview,
                message: 'Reseña creada exitosamente',
                code: Response::HTTP_CREATED
            );

        } catch (\Exception $e) {
            Log::error('Error al crear reseña: ' . $e->getMessage());
            return $this->error(
                message: 'Error al crear reseña',
                code: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Mostrar una reseña específica
     */
    public function show($id)
    {
        try {
            $userReview = UserReview::with([
                'reviewer:id,name,email,created_at',
                'reviewer.profile:id,user_id,first_name,last_name,avatar,phone,address,city,state,country,zip_code,bio',
                'user:id,name,email,created_at',
                'user.profile:id,user_id,first_name,last_name,avatar,phone,address,city,state,country,zip_code,bio',
                'review:id,rating,comment,status,created_at,updated_at'
            ])
                ->whereHas('review', function($q) {
                    $q->where('status', 'active');
                })
                ->find($id);

            if (!$userReview) {
                return $this->error(
                    message: 'Reseña no encontrada',
                    code: Response::HTTP_NOT_FOUND
                );
            }

            return $this->success(
                data: $userReview,
                message: 'Reseña obtenida exitosamente'
            );

        } catch (\Exception $e) {
            Log::error('Error al obtener reseña: ' . $e->getMessage());
            return $this->error(
                message: 'Error al obtener reseña',
                code: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Actualizar una reseña
     */
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'rating' => 'sometimes|numeric|min:0|max:5',
                'comment' => 'sometimes|nullable|string|max:1000'
            ]);

            if ($validator->fails()) {
                return $this->error(
                    message: 'Datos de entrada inválidos',
                    data: $validator->errors(),
                    code: Response::HTTP_BAD_REQUEST
                );
            }

            $userReview = UserReview::find($id);

            if (!$userReview) {
                return $this->error(
                    message: 'Reseña no encontrada',
                    code: Response::HTTP_NOT_FOUND
                );
            }

            // Verificar que solo el autor de la reseña pueda editarla
            if ($userReview->reviewer_id != Auth::id()) {
                return $this->error(
                    message: 'No tienes permisos para editar esta reseña',
                    code: Response::HTTP_FORBIDDEN
                );
            }

            // Actualizar la reseña
            $userReview->review->update([
                'rating' => $request->rating ?? $userReview->review->rating,
                'comment' => $request->comment ?? $userReview->review->comment
            ]);

            $userReview->load([
                'reviewer:id,name,email,created_at',
                'reviewer.profile:id,user_id,first_name,last_name,avatar,phone,address,city,state,country,zip_code,bio',
                'user:id,name,email,created_at',
                'user.profile:id,user_id,first_name,last_name,avatar,phone,address,city,state,country,zip_code,bio',
                'review:id,rating,comment,status,created_at,updated_at'
            ]);

            Log::info('Reseña actualizada', [
                'user_review_id' => $userReview->id,
                'reviewer_id' => Auth::id(),
                'rating' => $request->rating ?? $userReview->review->rating
            ]);

            return $this->success(
                data: $userReview,
                message: 'Reseña actualizada exitosamente'
            );

        } catch (\Exception $e) {
            Log::error('Error al actualizar reseña: ' . $e->getMessage());
            return $this->error(
                message: 'Error al actualizar reseña',
                code: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Eliminar una reseña
     */
    public function destroy($id)
    {
        try {
            $userReview = UserReview::find($id);

            if (!$userReview) {
                return $this->error(
                    message: 'Reseña no encontrada',
                    code: Response::HTTP_NOT_FOUND
                );
            }

            // Verificar que solo el autor de la reseña pueda eliminarla
            if ($userReview->reviewer_id != Auth::id()) {
                return $this->error(
                    message: 'No tienes permisos para eliminar esta reseña',
                    code: Response::HTTP_FORBIDDEN
                );
            }

            // Marcar la reseña como inactiva en lugar de eliminarla
            $userReview->review->update(['status' => 'inactive']);

            Log::info('Reseña marcada como inactiva', [
                'user_review_id' => $userReview->id,
                'reviewer_id' => Auth::id()
            ]);

            return $this->success(
                message: 'Reseña eliminada exitosamente'
            );

        } catch (\Exception $e) {
            Log::error('Error al eliminar reseña: ' . $e->getMessage());
            return $this->error(
                message: 'Error al eliminar reseña',
                code: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Obtener estadísticas de reseñas de un usuario
     */
    public function getStats($userId)
    {
        try {
            // Verificar que el usuario existe
            $user = User::find($userId);
            if (!$user) {
                return $this->error(
                    message: 'Usuario no encontrado',
                    code: Response::HTTP_NOT_FOUND
                );
            }

            // Obtener reseñas recibidas
            $receivedReviews = UserReview::where('user_id', $userId)
                ->whereHas('review', function($q) {
                    $q->where('status', 'active');
                })
                ->with('review')
                ->get();

            // Obtener reseñas realizadas
            $givenReviews = UserReview::where('reviewer_id', $userId)
                ->whereHas('review', function($q) {
                    $q->where('status', 'active');
                })
                ->with('review')
                ->get();

            // Calcular estadísticas
            $stats = [
                'received' => [
                    'total' => $receivedReviews->count(),
                    'average_rating' => $receivedReviews->count() > 0 ? round($receivedReviews->avg('review.rating'), 1) : 0,
                    'rating_distribution' => $this->getRatingDistribution($receivedReviews),
                    'recent_reviews' => $receivedReviews->take(5)->map(function($ur) {
                        return [
                            'id' => $ur->id,
                            'rating' => $ur->review->rating,
                            'comment' => $ur->review->comment,
                            'reviewer' => [
                                'id' => $ur->reviewer->id,
                                'name' => $ur->reviewer->name,
                                'profile' => $ur->reviewer->profile
                            ],
                            'created_at' => $ur->created_at
                        ];
                    })
                ],
                'given' => [
                    'total' => $givenReviews->count(),
                    'recent_reviews' => $givenReviews->take(5)->map(function($ur) {
                        return [
                            'id' => $ur->id,
                            'rating' => $ur->review->rating,
                            'comment' => $ur->review->comment,
                            'user' => [
                                'id' => $ur->user->id,
                                'name' => $ur->user->name,
                                'profile' => $ur->user->profile
                            ],
                            'created_at' => $ur->created_at
                        ];
                    })
                ]
            ];

            return $this->success(
                data: $stats,
                message: 'Estadísticas obtenidas exitosamente'
            );

        } catch (\Exception $e) {
            Log::error('Error al obtener estadísticas: ' . $e->getMessage());
            return $this->error(
                message: 'Error al obtener estadísticas',
                code: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Calcular distribución de calificaciones
     */
    private function getRatingDistribution($reviews)
    {
        $distribution = [
            '5' => 0, '4' => 0, '3' => 0, '2' => 0, '1' => 0, '0' => 0
        ];

        foreach ($reviews as $userReview) {
            $rating = (string) round($userReview->review->rating);
            if (isset($distribution[$rating])) {
                $distribution[$rating]++;
            }
        }

        return $distribution;
    }
} 