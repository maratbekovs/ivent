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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('floor_id')->constrained('floors')->onDelete('cascade'); // FK к floors
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('qr_code_path')->nullable(); // Путь к файлу QR-кода
            $table->timestamps();

            // Комбинация floor_id и name должна быть уникальной
            $table->unique(['floor_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};