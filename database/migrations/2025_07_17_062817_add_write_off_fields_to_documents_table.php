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
        Schema::table('documents', function (Blueprint $table) {
            // ИСПРАВЛЕНО: Добавляем столбцы после существующих полей, а не user_id

            if (!Schema::hasColumn('documents', 'title')) {
                // Добавляем после creator_id, так как он точно существует
                $table->string('title')->nullable()->after('creator_id');
            }
            if (!Schema::hasColumn('documents', 'type')) {
                $table->string('type')->default('other')->after('title');
            }
            if (!Schema::hasColumn('documents', 'reason')) {
                $table->text('reason')->nullable()->after('type');
            }
            if (!Schema::hasColumn('documents', 'commission_data')) {
                $table->json('commission_data')->nullable()->after('reason');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            if (Schema::hasColumn('documents', 'commission_data')) {
                $table->dropColumn('commission_data');
            }
            if (Schema::hasColumn('documents', 'reason')) {
                $table->dropColumn('reason');
            }
            if (Schema::hasColumn('documents', 'type')) {
                $table->dropColumn('type');
            }
            if (Schema::hasColumn('documents', 'title')) {
                $table->dropColumn('title');
            }
        });
    }
};
