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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->enum('document_type', ['acceptance', 'disposal', 'repair_request', 'inventory_order']);
            $table->foreignId('asset_id')->nullable()->constrained('assets')->onDelete('set null');
            $table->foreignId('related_request_id')->nullable()->constrained('requests')->onDelete('set null');
            $table->foreignId('creator_id')->constrained('users')->onDelete('restrict');

            $table->string('file_path');
            $table->foreignId('signed_by_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('signed_at')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};