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
        // Tabla intermedia para la relación muchos a muchos
        Schema::create('user_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Usuario que recibe la reseña
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade'); // Usuario que hace la reseña
            $table->foreignId('review_id')->constrained()->onDelete('cascade'); // ID de la reseña
            $table->timestamps();
            
            // Índices para mejorar el rendimiento
            $table->index(['user_id', 'review_id']);
            $table->index(['reviewer_id', 'review_id']);
            
            // Un usuario solo puede hacer una reseña por usuario
            $table->unique(['user_id', 'reviewer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_reviews');
    }
}; 