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
        // Tabla principal de reseñas
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->decimal('rating', 2, 1)->default(0.0); // Valoración de 0.0 a 5.0
            $table->text('comment')->nullable(); // Comentario de la reseña
            $table->enum('status', ['active', 'inactive'])->default('active'); // Estado de la reseña
            $table->timestamps();
            
            // Índices para mejorar el rendimiento
            $table->index('status');
            $table->index('rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
