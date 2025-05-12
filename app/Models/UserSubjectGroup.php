<?php

namespace App\Models;

use App\Models\Scopes\PositionScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class UserSubjectGroup extends Model {
    use HasFactory;

    protected static function booted() {
        static::addGlobalScope(new PositionScope);
    }

    public $timestamps = false;

    public $fillable = [
        'user_id',
        'subject_group_id',
        'sort_order'
    ];

    public function group(): BelongsTo {
        return $this->belongsTo(SubjectGroup::class, 'subject_group_id', 'id');
    }

    public function subjects(): BelongsToMany {
        return $this->belongsToMany(Subject::class, 'user_subject', 'user_id', 'subject_id')
            ->where('user_subject.user_id', $this->user_id)
            ->withPivot('id', 'description', 'image', 'sort_order')
            ->orderBy('sort_order');
    }

    public function userSubjects(): HasManyThrough {
        return $this->hasManyThrough(
            UserSubject::class,
            Subject::class,
            'subject_group_id', // Clave foránea en subjects que apunta a subject_groups
            'subject_id',      // Clave foránea en user_subject que apunta a subjects
            'subject_group_id', // Clave local en user_subject_groups
            'id'              // Clave local en subjects
        );
    }
}
