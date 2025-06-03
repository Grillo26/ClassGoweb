<?php

namespace App\Services\interfaces;

interface ISlotBookingService
{
   


    /**
     * Obtiene la lista de tutorias completadas por usuraios tutores o estudiantes
     
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSlotBookingByUserId();


    public function bookSlot($slotId, $userId, $additionalData = []);

   
}
