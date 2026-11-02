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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code', 10)->unique(); // Kode Unik cth: FTS-9K82L
            $table->foreignId('field_id')->constrained('fields')->onDelete('cascade');
            $table->string('customer_name', 100);
            $table->string('customer_phone', 20);
            $table->date('booking_date');
            $table->decimal('total_price', 12, 2);
            $table->enum('payment_method', ['transfer', 'cash']);
            $table->enum('payment_status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->enum('booking_status', ['reserved', 'confirmed', 'completed', 'cancelled'])->default('reserved');
            $table->string('proof_of_payment')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();

            // Indexes untuk performa anti double booking & pencarian kasir
            $table->index('booking_code');
            $table->index(['field_id', 'booking_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
