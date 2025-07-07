<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         Schema::create('payment_slot_bookings', function (Blueprint $table) {
            $table->id();
            $table->text('image_url');
            $table->unsignedBigInteger('slot_booking_id'); // Declaración de la columna
            $table->foreign('slot_booking_id')              // Clave foránea correctamente definida
                  ->references('id')
                  ->on('slot_bookings')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_slot_bookings');
    }
};
