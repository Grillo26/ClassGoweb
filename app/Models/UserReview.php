<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reviewer_id',
        'review_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Usuario que recibe la reseña
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Usuario que hace la reseña
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    /**
     * La reseña asociada
     */
    public function review(): BelongsTo
    {
        return $this->belongsTo(Review::class, 'review_id');
    }

    /**
     * Scope para reseñas activas
     */
    public function scopeActive($query)
    {
        return $query->whereHas('review', function($q) {
            $q->where('status', 'active');
        });
    }

    /**
     * Scope para reseñas inactivas
     */
    public function scopeInactive($query)
    {
        return $query->whereHas('review', function($q) {
            $q->where('status', 'inactive');
        });
    }

    /**
     * Verificar si un usuario ya ha reseñado a otro usuario
     */
    public static function hasUserReviewed($reviewerId, $userId)
    {
        return self::where('reviewer_id', $reviewerId)
                   ->where('user_id', $userId)
                   ->whereHas('review', function($q) {
                       $q->where('status', 'active');
                   })
                   ->exists();
    }

    /**
     * Obtener el promedio de valoraciones de un usuario
     */
    public static function getAverageRating($userId)
    {
        return self::where('user_id', $userId)
                   ->whereHas('review', function($q) {
                       $q->where('status', 'active');
                   })
                   ->join('reviews', 'user_reviews.review_id', '=', 'reviews.id')
                   ->avg('reviews.rating') ?? 0.0;
    }

    /**
     * Obtener el total de reseñas de un usuario
     */
    public static function getTotalReviews($userId)
    {
        return self::where('user_id', $userId)
                   ->whereHas('review', function($q) {
                       $q->where('status', 'active');
                   })
                   ->count();
    }
} 