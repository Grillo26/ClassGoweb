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
            $table->string('meeting_link')->nullable()->after('calendar_event_id');
            $table->unsignedBigInteger('user_subject_slot_id')->after('tutor_id');
            $table->foreign('user_subject_slot_id')->references('id')->on('user_subject_slots')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('slot_bookings', function (Blueprint $table) {
            $table->dropForeign(['user_subject_slot_id']);
            $table->dropColumn('user_subject_slot_id');
            $table->dropColumn('meeting_link');
        });
    }
};
