<?php


namespace App\Services;

use DB;


class ReservasService
{
    public function __construct(
        private ImagenesService $imageService,
        private PagosTutorReservaService $pagostutorreserva,
        private MailService $emailService
    ) {
    }
    public function reservarSlotBoooking(
        $slot,
        $subjectId = null,
        $selectedHour = null,
        $uploadedImage, 
        $bookedSlot, 
        $sessionFee,
        $user,
        $selectedSubject
    )
    {
        try {
            BD::beginTransaction();
            $dateOnly = $slot->date->format('Y-m-d');
            $timeOnly = $selectedHour ?? $slot->start_time->format('H:i:s');
            $dateTimeString = $dateOnly . ' ' . $timeOnly;
            $startDateTime = Carbon::parse($dateTimeString);
            $endDateTime = $startDateTime->copy()->addMinutes(20);
            $slotBooking = SlotBooking::create([
                'student_id' => Auth::user()->id, 
                'tutor_id' => $slot->user_id,   
                'user_subject_slot_id' => $slot->id,        // ID del slot obligatorio
                'session_fee' => 15,               // Tarifa de la sesión (por defecto 15)
                'booked_at' => now(),            // Fecha y hora de la reserva
                'start_time' => $startDateTime,   // Hora de inicio
                'end_time' => $endDateTime,     // Hora de fin
                'meeting_link' => null,             // Link nulo al crear
                'subject_id' => $subjectId,       // Usar el subject_id recibido
                'status' => 2                 // Estado Pendiente
            ]);
            if ($uploadedImage) {
                $path = $this->imageService->guardarqrEstudianteReserva($uploadedImage);
                PaymentSlotBooking::create([
                    'slot_booking_id' => $bookedSlot->id,
                    'image_url' => $path,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $this->pagostutorreserva->create(
                    slot_booking_id: $bookedSlot->id,
                    payment_date: now(),
                    amount: $sessionFee,
                    message: '',
                ); 
            }
            DB::commit();
            $this->emailService->sendAdminNuevaTutoria($user?->profile?->full_name, $selectedSubject);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e; // Re-lanzar la excepción para manejarla en el controlador
        }
        //return $slotBooking;
    }
}
