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
        // Проверяем, существует ли неправильный столбец 'user_id', и если да, удаляем его.
        if (Schema::hasColumn('requests', 'user_id')) {
            Schema::table('requests', function (Blueprint $table) {
                // Поскольку внешний ключ мог не установиться,
                // безопаснее просто удалить столбец.
                $table->dropColumn('user_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Откат не требуется, так как мы исправляем ошибку.
        // Но для полноты можно было бы добавить столбец обратно.
        // Schema::table('requests', function (Blueprint $table) {
        //     $table->foreignId('user_id')->nullable();
        // });
    }
};
