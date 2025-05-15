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
        Schema::table('payment_slot_bookings', function (Blueprint $table) {
            $table->foreignId('slot_booking_id')->constrained('slot_bookings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_slot_bookings', function (Blueprint $table) {
            $table->dropForeign(['slot_booking_id']);
            $table->dropColumn('slot_booking_id');
        });
    }
};
