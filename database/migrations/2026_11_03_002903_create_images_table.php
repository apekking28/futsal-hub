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
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('imageable_type', 100); // Model App\Models\Field atau App\Models\Gor
            $table->unsignedBigInteger('imageable_id');
            $table->string('path');                // Path gambar utama (WebP/JPG)
            $table->string('thumb_path')->nullable(); // Path thumbnail
            $table->string('alt_text', 150)->nullable();
            $table->boolean('is_cover')->default(false);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            // Index Polimorfik untuk Query Cepat
            $table->index(['imageable_type', 'imageable_id', 'sort_order'], 'idx_imageable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
