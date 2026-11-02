<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Buat tabel gors
        Schema::create('gors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('district_id')->constrained('districts')->onDelete('cascade');
            $table->string('name', 150);
            $table->string('slug', 150)->unique();
            $table->text('address');
            $table->string('phone_number', 20);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Hubungkan FK gor_id di tabel users
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('gor_id')->references('id')->on('gors')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['gor_id']);
        });
        Schema::dropIfExists('gors');
    }
};
