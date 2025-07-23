<?php

namespace App\Models;

use App\Casts\BookingStatus;
use App\Jobs\DeleteGoogleCalendarEvent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class SlotBooking extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $guarded = [];

    // Campo adicional para el link de la tutoría
    // 'meeting_link' es string|null

    public static function boot() {
        parent::boot();
        self::deleting(function($booking) {
            $booking->bookingLog()->delete();
            if ($booking->status == 'active') {
                dispatch(new DeleteGoogleCalendarEvent($booking->booker, $booking->meta_data['event_id'] ?? null));
            }
        });
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status'    => BookingStatus::class,
            'meta_data' => 'array'
        ];
    }

    /**
     * Obtiene las reservas del usuario logueado.
     *
     * @param int $studentId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getBookingsByStudent($studentId)
    {
        return self::where('student_id', $studentId)
            ->select('id', 'start_time', 'end_time', 'status')
            ->get();
    }

    public function booker(): BelongsTo {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function bookee(): BelongsTo {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    public function student(): HasOneThrough {
        return $this->hasOneThrough(Profile::class, User::class, 'id', 'user_id', 'student_id', 'id');
    }

    public function slot()
    {
        return $this->belongsTo(UserSubjectSlot::class, 'user_subject_slot_id');
    }

    public function tutor(): HasOneThrough {
        return $this->hasOneThrough(Profile::class, User::class, 'id', 'user_id', 'tutor_id', 'id');
    }

    public function rating(): MorphOne {
        return $this->morphOne(Rating::class, 'ratingable')->latest()->take(1);
    }

    public function orderItem(): MorphOne {
        return $this->morphOne(OrderItem::class, 'orderable')->latest()->take(1);
    }

    public function bookingLog(): HasMany {
        return $this->hasMany(BookingLog::class, 'booking_id');
    }
    public function dispute(): HasOne {
        return $this->hasOne(Dispute::class, 'disputable_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function paymentSlotBooking()
    {
        return $this->hasOne(PaymentSlotBooking::class, 'slot_booking_id');
    }

    public function claims(): HasMany
    {
        return $this->hasMany(Claim::class);
    }

    // Relación uno a uno: cada reserva tiene un solo pago
    public function payment(): HasOne
    {
        return $this->hasOne(SlotPayment::class, 'slot_booking_id');
    }
}
