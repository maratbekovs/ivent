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
        Schema::table('assets', function (Blueprint $table) {
            // Проверяем, существует ли столбец, прежде чем переименовывать
            if (Schema::hasColumn('assets', 'qr_code_path')) {
                $table->renameColumn('qr_code_path', 'qr_code_data');
            }
            // Если нужно изменить тип, можно сделать это так:
            // $table->string('qr_code_data')->nullable()->change();
        });

        Schema::table('rooms', function (Blueprint $table) {
            // Проверяем, существует ли столбец, прежде чем переименовывать
            if (Schema::hasColumn('rooms', 'qr_code_path')) {
                $table->renameColumn('qr_code_path', 'qr_code_data');
            }
            // Если нужно изменить тип, можно сделать это так:
            // $table->string('qr_code_data')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            // Проверяем, существует ли столбец, прежде чем переименовывать обратно
            if (Schema::hasColumn('assets', 'qr_code_data')) {
                $table->renameColumn('qr_code_data', 'qr_code_path');
            }
        });

        Schema::table('rooms', function (Blueprint $table) {
            // Проверяем, существует ли столбец, прежде чем переименовывать обратно
            if (Schema::hasColumn('rooms', 'qr_code_data')) {
                $table->renameColumn('qr_code_data', 'qr_code_path');
            }
        });
    }
};
