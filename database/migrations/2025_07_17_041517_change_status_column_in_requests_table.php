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
        Schema::table('requests', function (Blueprint $table) {
            // Принудительно изменяем тип столбца на VARCHAR(255)
            $table->string('status')->default('new')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            // При откате можно оставить как есть, или вернуть к предыдущему состоянию, если оно известно.
            // Для безопасности просто оставим изменение.
        });
    }
};
