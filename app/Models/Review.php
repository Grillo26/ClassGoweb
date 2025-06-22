<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'rating',
        'comment',
        'status'
    ];

    protected $casts = [
        'rating' => 'decimal:1',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relación con la tabla intermedia
     */
    public function userReviews(): HasMany
    {
        return $this->hasMany(UserReview::class);
    }

    /**
     * Usuarios que reciben esta reseña
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_reviews', 'review_id', 'user_id');
    }

    /**
     * Usuarios que hacen esta reseña
     */
    public function reviewers()
    {
        return $this->belongsToMany(User::class, 'user_reviews', 'review_id', 'reviewer_id');
    }

    /**
     * Scope para reseñas activas
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope para reseñas inactivas
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Scope para filtrar por valoración mínima
     */
    public function scopeMinRating($query, $rating)
    {
        return $query->where('rating', '>=', $rating);
    }

    /**
     * Scope para filtrar por valoración máxima
     */
    public function scopeMaxRating($query, $rating)
    {
        return $query->where('rating', '<=', $rating);
    }

    /**
     * Obtener el promedio de valoraciones de un usuario
     */
    public static function getAverageRating($userId)
    {
        return self::where('user_id', $userId)
                   ->where('status', 'active')
                   ->avg('rating') ?? 0.0;
    }

    /**
     * Obtener el total de reseñas de un usuario
     */
    public static function getTotalReviews($userId)
    {
        return self::where('user_id', $userId)
                   ->where('status', 'active')
                   ->count();
    }

    /**
     * Verificar si un usuario ya ha reseñado a otro usuario
     */
    public static function hasUserReviewed($reviewerId, $userId)
    {
        return self::where('reviewer_id', $reviewerId)
                   ->where('user_id', $userId)
                   ->exists();
    }
} 