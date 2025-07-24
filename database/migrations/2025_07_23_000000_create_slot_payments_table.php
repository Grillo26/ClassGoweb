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
        Schema::create('slot_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('slot_booking_id')->constrained('slot_bookings')->onDelete('cascade');
            $table->date('payment_date');
            $table->string('payment_method');
            $table->decimal('amount', 10, 2);
            $table->tinyInteger('status')->default(1)->comment('1-> Pendiente, 2-> Pagado, 3-> Observado, 4-> Cancelado');
            $table->text('message')->nullable();
            $table->string('receipt_pdf')->nullable()->comment('Ruta del comprobante PDF, si se almacena');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slot_payments');
    }
};
