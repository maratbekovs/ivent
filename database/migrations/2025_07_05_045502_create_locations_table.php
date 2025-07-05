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
        Schema::create('locations', function (Blueprint $table) {
            $table->id(); // PK, AUTO_INCREMENT
            $table->string('name')->unique(); // Название корпуса, уникальное
            $table->string('address')->nullable(); // Адрес корпуса, может быть пустым
            $table->text('description')->nullable(); // Описание, может быть пустым
            $table->timestamps(); // created_at и updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};