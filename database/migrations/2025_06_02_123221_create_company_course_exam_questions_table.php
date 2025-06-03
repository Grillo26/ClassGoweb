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
       Schema::create('company_course_exam_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_course_exam_id')->constrained()->onDelete('cascade');
            $table->text('question');
            $table->string('type')->default('text'); // text, multiple_choice, etc.
            $table->integer('score')->default(1);
            $table->json('options')->nullable(); // Para preguntas de opción múltiple
            $table->string('correct_answer')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_course_exam_questions');
    }
};
