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
        Schema::create('company_course_exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_course_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->integer('total_score')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_course_exams');
    }
};
