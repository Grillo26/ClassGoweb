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
        Schema::table('slot_bookings', function (Blueprint $table) {
            // Eliminar la columna user_subject_slot_id
            $table->dropForeign(['user_subject_slot_id']);
            $table->dropColumn('user_subject_slot_id');

            // Modificar la columna status para usar los nuevos valores
            $table->string('status')->default('pendiente')->comment('pendiente, rechazado, aceptado, no_completado, completado')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('slot_bookings', function (Blueprint $table) {
            // Restaurar la columna user_subject_slot_id
            $table->foreignId('user_subject_slot_id')->constrained();

            // Restaurar la columna status a su estado anterior
            $table->tinyInteger('status')->default(1)->comment('1-> Active, 2-> Rescheduled, 3-> Refunded, 4-> Reserved, 5-> Completed')->change();
        });
    }
};