<?php

namespace App\Services;

use App\Models\UserSubjectSlot;
use App\Models\Reservation; // ajusta si tu modelo se llama distinto
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class TimeSlotService
{
    /**
     * Devuelve un arreglo indexado por día del mes:
     * [
     *   8 => [ ['time'=>'10:00','status'=>'free'], ... ],
     *   15 => [ ... ],
     * ]
     */
    public function getTimeSlotsByDay(int $tutorId, Carbon $month): array
    {
        $startOfMonth = $month->copy()->startOfMonth();
        $endOfMonth   = $month->copy()->endOfMonth();

        // 1) Slots de disponibilidad del tutor en el mes
        $slots = UserSubjectSlot::query()
            ->where('user_id', $tutorId)
            ->whereBetween('date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->get();

        // 2) Reservas existentes del mes (para marcar occupied)
        $reservations = Reservation::query()
            ->where('tutor_id', $tutorId) // ajusta si tu campo se llama diferente
            ->whereBetween('date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->get()
            ->groupBy(function ($r) {
                return Carbon::parse($r->date)->day; // día del mes (1..31)
            })
            ->map(function ($dayReservations) {
                // Mapa rápido: 'HH:ii' => true
                return $dayReservations->mapWithKeys(function ($r) {
                    $timeStr = Carbon::parse($r->start_time)->format('H:i');
                    return [$timeStr => true];
                });
            });

        $result = [];

        foreach ($slots as $slot) {
            $date = Carbon::parse($slot->date);
            $day  = $date->day;

            // Genera intervalos desde start_time hasta end_time, saltando por "duracion" minutos.
            // Usamos end_time - duracion para que el último inicio no se pase del fin.
            $period = CarbonPeriod::create(
                Carbon::parse($slot->start_time),
                "{$slot->duracion} minutes",
                Carbon::parse($slot->end_time)->subMinutes($slot->duracion)
            );

            foreach ($period as $time) {
                $timeStr    = $time->format('H:i');
                $isOccupied = optional($reservations->get($day))[$timeStr] ?? false;

                $result[$day][] = [
                    'time'   => $timeStr,
                    'status' => $isOccupied ? 'occupied' : 'free',
                ];
            }
        }

        // Ordena horarios dentro de cada día y los días del mes
        foreach ($result as $day => &$arr) {
            usort($arr, fn ($a, $b) => strcmp($a['time'], $b['time']));
        }
        ksort($result);

        return $result;
    }
}
