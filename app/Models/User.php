<?php

namespace App\Models;

use Amentotech\LaraGuppy\Traits\Chatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Casts\UserStatusCast;
use App\Jobs\SendNotificationJob;
use App\Notifications\EmailNotification;
use App\Services\NotificationService;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Dispute;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail, CanResetPasswordContract
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens, Chatable, CanResetPassword;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'email_verified_at',
        'fcm_token',
        'available_for_tutoring',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => UserStatusCast::class,
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'available_for_tutoring' => 'boolean',
        ];
    }

    /**
     * Scope a query to only include active users.
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('status', 1);
    }

    /**
     * Scope a query to only include inactive users.
     */
    public function scopeInactive(Builder $query): void
    {
        $query->where('status', 0);
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
    public function accountSetting(): HasMany
    {
        return $this->hasMany(AccountSetting::class, 'user_id');
    }

    public function identityVerification(): HasOne
    {
        return $this->hasOne(UserIdentityVerification::class);
    }

    public function groups(): HasMany
    {
        return $this->hasMany(UserSubjectGroup::class)->orderBy('sort_order');
    }

    /**
     * Reseñas que recibe el usuario (como tutor)
     */
    public function receivedReviews(): HasMany
    {
        return $this->hasMany(UserReview::class, 'user_id');
    }

    /**
     * Reseñas que hace el usuario (como estudiante)
     */
    public function givenReviews(): HasMany
    {
        return $this->hasMany(UserReview::class, 'reviewer_id');
    }

    /**
     * Relación muchos a muchos con reseñas (recibidas)
     */
    public function reviews()
    {
        return $this->belongsToMany(Review::class, 'user_reviews', 'user_id', 'review_id');
    }

    /**
     * Relación muchos a muchos con reseñas (dadas)
     */
    public function reviewsGiven()
    {
        return $this->belongsToMany(Review::class, 'user_reviews', 'reviewer_id', 'review_id');
    }

    public function role(): Attribute
    {
        return Attribute::make(
            get: fn() => Cache::rememberForever('user-role-' . $this->id, function() {
                try {
                    // Verificar si roles ya está cargado
                    if ($this->relationLoaded('roles')) {
                        if ($this->roles instanceof \Illuminate\Database\Eloquent\Collection && $this->roles->count() > 0) {
                            return $this->roles->first()->name;
                        }
                        return null;
                    }
                    
                    // Si no está cargado, hacer una consulta directa
                    $role = $this->roles()->select('name')->first();
                    return $role ? $role->name : null;
                } catch (\Exception $e) {
                    \Log::error('Error en atributo role para usuario ' . $this->id . ': ' . $e->getMessage());
                    return null;
                }
            }),
        );
    }

    public static function admin()
    {
        return self::with('profile:id,user_id,first_name,last_name,image')->role('admin')->first();
    }

    public function address(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable')->where('addressable_type', User::class);
    }

    public function redirectAfterLogin(): Attribute
    {
        return Attribute::make(
            get: fn() => match ($this->role) {
                'admin' => route('admin.insights', absolute: false),
                'tutor' => route('tutor.dashboard', absolute: false),
                'student' => route('student.bookings', absolute: false),
                default => url('/')
            },
        );
    }

    public function isOnline(): Attribute
    {
        return Attribute::make(
            get: fn() => Cache::has('user-online-' . $this->id),
        );
    }

    public function educations()
    {
        return $this->hasMany(UserEducation::class);
    }

    /**
     * Get the experiences for the user.
     */
    public function experiences()
    {
        return $this->hasMany(UserExperience::class);
    }

    /**
     * Get the certificates for the user.
     */
    public function certificates()
    {
        return $this->hasMany(UserCertificate::class);
    }

    public function languages(): BelongsToMany
    {
        return $this->belongsToMany(Language::class, 'user_languages');
    }

    public function tuitionSetting(): HasOne
    {
        return $this->hasOne(TuitionSetting::class);
    }

    public function billingDetail(): HasOne
    {
        return $this->hasOne(BillingDetail::class);
    }

    public function favouriteUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favourite_users', 'user_id', 'favourite_user_id');
    }

    public function favouriteByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favourite_users', 'favourite_user_id', 'user_id');
    }

    public function bookingSlots(): HasMany
    {
        return $this->hasMany(SlotBooking::class, 'tutor_id');
    }

    public function bookingOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    public function userPayouts()
    {
        return $this->hasMany(UserPayoutMethod::class);
    }

    public function wallet()
    {
        return $this->hasOne(UserWallet::class);
    }

    public function withdrawals(): HasMany
    {
        return $this->hasMany(UserWithdrawal::class);
    }

    // Relationship for pending withdrawals
    public function pendingWithdrawals(): HasMany
    {
        return $this->Withdrawals()->where('status', 'pending');
    }

    // Relationship for completed withdrawals
    public function completedWithdrawals(): HasMany
    {
        return $this->Withdrawals()->where('status', 'paid');
    }

    public function sendPasswordResetNotification($token)
    {
        dispatch(new SendNotificationJob('passwordResetRequest', $this, ['token' => $token, 'userEmail' => $this->email, 'userName' => $this->profile->full_name]));
    }

    public function userWallet(): HasOne
    {
        return $this->hasOne(UserWallet::class, 'user_id');
    }

    public function createdDisputes()
    {
        return $this->hasMany(Dispute::class, 'creator_by');
    }

    public function responsibleDisputes()
    {
        return $this->hasMany(Dispute::class, 'responsible_by');
    }

    public function socialProfiles(): HasMany
    {
        return $this->hasMany(SocialProfile::class);
    }

    /**
     * Get the badges that the user has.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function badges(): BelongsToMany|null
    {
        if (\Nwidart\Modules\Facades\Module::has('starup') && \Nwidart\Modules\Facades\Module::isEnabled('starup')) {
            return $this->belongsToMany(\Modules\Starup\Models\Badge::class, getStarupDbPrefix() . 'user_badges', 'user_id', 'badge_id');
        }
        return null;
    }

    /**
     * Get the courses for the user.
     */
    public function companyCourseUsers(): HasMany
    {
        return $this->hasMany(CompanyCourseUser::class, 'user_id');

    }

    /**
     * Get the count of completed courses for the user.
     */
    public function getCompletedCoursesCount(): int
    {
        return $this->companyCourseUsers()
            ->where('status', 'completed')
            ->count();
    }

    public function userSubjects(): HasMany
    {
        return $this->hasMany(UserSubject::class);
    }

    public function getUserGroupSubjects($groupId)
    {
        // Buscar los user_subjects que pertenezcan a este grupo
        $subjects = UserSubject::where('user_id', $this->user->id)
            ->whereHas('subject', function ($q) use ($groupId) {
                $q->where('subject_group_id', $groupId);
            })
            ->with('subject')
            ->get();

        // Retornar un array id => nombre
        return $subjects->pluck('subject.name', 'subject.id')->toArray();
    }

    public function setUserSubject($id, $subject)
    {
        // Buscar el registro de UserSubject por id y user_id
        $userSubject = UserSubject::where('id', $id)
            ->where('user_id', $this->user->id)
            ->first();

        if ($userSubject) {
            $userSubject->update($subject);
            return $userSubject;
        } else {
            return UserSubject::create(array_merge($subject, ['user_id' => $this->user->id]));
        }
    }

    public function deteletSubject($userGroupId, $userSubjectId)
    {
        // Eliminar el UserSubject por id y user_id
        $userSubject = UserSubject::where('id', $userSubjectId)
            ->where('user_id', $this->user->id)
            ->first();

        if ($userSubject) {
            return $userSubject->delete();
        }
        return null;
    }

    public function deleteUserSubjectGroup($groupId): bool
    {
        // Eliminar todos los UserSubject del grupo para este usuario
        $userSubjects = UserSubject::where('user_id', $this->user->id)
            ->whereHas('subject', function ($q) use ($groupId) {
                $q->where('subject_group_id', $groupId);
            })->get();

        foreach ($userSubjects as $userSubject) {
            $userSubject->delete();
        }

        // Eliminar el grupo de usuario
        $group = $this->user->groups()->whereId($groupId)->first();
        if ($group) {
            $group->delete();
            return true;
        }
        return false;
    }

    public function subjects()
    {
        return $this->belongsToMany(
            \App\Models\Subject::class,
            'user_subject',
            'user_id',
            'subject_id'
        );
    }


    public function userSubjectSlots()
    {
        return $this->hasMany(UserSubjectSlot::class, 'user_id');
    }

    /*<------ CODIGOS Y CUPONES ------->*/
    public function codes()
    {
        return $this->hasMany(Code::class);
    }

    public function userCoupons()
    {
        return $this->hasMany(UserCoupon::class);
    }

    public function coupons()
    {
        return $this->belongsToMany(Coupon::class, 'user_coupons')->withPivot('cantidad')->withTimestamps();
    }

}
