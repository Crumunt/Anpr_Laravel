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
        Schema::create('flagged_vehicles', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('plate_number', 20)->index();
            $table->string('reason', 50); // suspicious, expired, unauthorized, stolen, other
            $table->string('reason_label', 100)->nullable(); // Human-readable reason
            $table->enum('priority', ['high', 'medium', 'low'])->default('medium');
            $table->text('notes')->nullable();
            $table->enum('status', ['active', 'resolved', 'dismissed'])->default('active');

            // Vehicle details (optional, for when vehicle is not in database)
            $table->string('vehicle_make', 50)->nullable();
            $table->string('vehicle_model', 50)->nullable();
            $table->string('vehicle_color', 30)->nullable();
            $table->string('vehicle_type', 30)->nullable();

            // Reference to related ANPR record (optional)
            $table->string('record_id', 36)->nullable();
            $table->foreign('record_id')->references('id')->on('records')->onDelete('set null');

            // Who flagged it
            $table->string('flagged_by_id', 36)->nullable();
            $table->foreign('flagged_by_id')->references('id')->on('users')->onDelete('set null');
            $table->string('flagged_by_name', 100)->nullable();
            $table->string('flagged_by_role', 50)->nullable();

            // Resolution details
            $table->string('resolved_by_id', 36)->nullable();
            $table->foreign('resolved_by_id')->references('id')->on('users')->onDelete('set null');
            $table->string('resolved_by_name', 100)->nullable();
            $table->text('resolution_notes')->nullable();
            $table->timestamp('resolved_at')->nullable();

            $table->timestamps();

            // Indexes
            $table->index(['status', 'priority']);
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flagged_vehicles');
    }
};
