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
            // Проверяем, существует ли столбец 'user_id', и только потом добавляем
            if (!Schema::hasColumn('requests', 'user_id')) {
                $table->foreignId('user_id')
                      ->nullable() // Позволяет столбцу быть пустым для старых записей
                      ->constrained()
                      ->onDelete('set null'); // При удалении пользователя, его заявки останутся
            }

            // Проверяем, существует ли столбец 'subject', и только потом добавляем
            if (!Schema::hasColumn('requests', 'subject')) {
                $table->string('subject');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            // Проверяем наличие столбцов перед удалением, чтобы избежать ошибок при откате
            if (Schema::hasColumn('requests', 'subject')) {
                $table->dropColumn('subject');
            }
            if (Schema::hasColumn('requests', 'user_id')) {
                // Laravel достаточно умен, чтобы сначала удалить внешний ключ
                $table->dropConstrainedForeignId('user_id');
            }
        });
    }
};
