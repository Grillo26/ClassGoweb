<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserLanguage extends Model {
    use HasFactory;

    protected $fillable = [
        'user_id',
        'language_id'
    ];

    /**
     * Get the language associated with the UserLanguage
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function language(): BelongsTo {
        return $this->belongsTo(Language::class);
    }

    /**
     * Get the user associated with the UserLanguage
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
