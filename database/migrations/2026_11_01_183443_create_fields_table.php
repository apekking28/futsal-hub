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
    Schema::create('fields', function (Blueprint $table) {
    $table->id();
    $table->foreignId('gor_id')->constrained('gors')->onDelete('cascade');
    $table->string('name');
    $table->string('type', 100)->default('Vinyl'); // Kolom type ditambahkan di sini
    $table->decimal('price_per_hour', 12, 2);
    $table->boolean('is_active')->default(true);
    $table->text('description')->nullable();
    $table->timestamps();
});
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fields');
    }
};
