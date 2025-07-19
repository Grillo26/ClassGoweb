<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SlotBookingCreated implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $slotBookingId;
    public $studentId;
    public $tutorId;
    public $startTime;
    public $endTime;
    public $sessionFee;

    /**
     * Create a new event instance.
     */
    public function __construct($slotBookingId, $studentId, $tutorId, $startTime, $endTime, $sessionFee)
    {
        $this->slotBookingId = $slotBookingId;
        $this->studentId = $studentId;
        $this->tutorId = $tutorId;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->sessionFee = $sessionFee;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel
     */
    public function broadcastOn()
    {
        Log::info('SlotBookingCreated: evento disparado', [
            'slotBookingId' => $this->slotBookingId,
            'studentId' => $this->studentId,
            'tutorId' => $this->tutorId
        ]);

        // Buscar información del tutor para la notificación
        $tutor = \App\Models\User::find($this->tutorId);
        
        if ($tutor) {
            $fcmService = new \App\Services\FcmService();
            
            // Notificar al estudiante que creó la tutoría
            $student = \App\Models\User::find($this->studentId);
            if ($student && $student->fcm_token) {
                Log::info('Enviando notificación FCM al estudiante - Tutoría creada', [
                    'user_id' => $student->id,
                    'fcm_token' => $student->fcm_token
                ]);
                
                $fcmService->sendNotification(
                    $student->fcm_token,
                    'Tutoría creada exitosamente',
                    'Tu tutoría con ' . $tutor->first_name . ' ha sido creada',
                    [
                        'icon' => 'tutoria_creada',
                        'slotBookingId' => $this->slotBookingId,
                        'type' => 'booking_created'
                    ]
                );
            } else {
                Log::warning('No se encontró fcm_token para el estudiante', ['student' => $this->studentId]);
            }
        }

        // Retornar canal privado solo para el estudiante que creó la tutoría
        return new PrivateChannel('user.' . $this->studentId);
    }

    /**
     * Get the broadcast event name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'SlotBookingCreated';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'slotBookingId' => $this->slotBookingId,
            'studentId' => $this->studentId,
            'tutorId' => $this->tutorId,
            'startTime' => $this->startTime,
            'endTime' => $this->endTime,
            'sessionFee' => $this->sessionFee,
            'message' => 'Tutoría creada exitosamente',
            'type' => 'booking_created'
        ];
    }
} 