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
        Schema::create('inventory_session_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_session_id')->constrained('inventory_sessions')->onDelete('cascade');
            $table->foreignId('asset_id')->constrained('assets')->onDelete('cascade');
            $table->foreignId('expected_room_id')->nullable()->constrained('rooms')->onDelete('set null');
            $table->foreignId('found_in_room_id')->constrained('rooms')->onDelete('cascade');
            $table->enum('status', ['found', 'missing', 'extra'])->comment('found: expected and scanned; missing: expected but not scanned; extra: not expected but scanned');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_session_items');
    }
};