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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('asset_categories')->onDelete('restrict');
            $table->foreignId('room_id')->nullable()->constrained('rooms')->onDelete('set null');
            $table->foreignId('current_user_id')->nullable()->constrained('users')->onDelete('set null');

            $table->string('serial_number')->unique();
            $table->string('inventory_number')->unique()->nullable();
            $table->string('mac_address')->nullable();
            $table->year('purchase_year')->nullable();
            $table->date('warranty_expires_at')->nullable();
            $table->foreignId('asset_status_id')->constrained('asset_statuses')->onDelete('restrict');

            $table->string('qr_code_path')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};