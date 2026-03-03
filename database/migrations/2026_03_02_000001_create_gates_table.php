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
        Schema::create('gates', function (Blueprint $table) {
            $table->id();
            $table->string('gate_name', 50);          // e.g., "Main Gate", "Second Gate", "Visionary Gate", "BGC Gate"
            $table->string('gate_location', 20);      // "Entry" or "Exit"
            $table->string('slug', 100)->unique();    // e.g., "main-gate-entry", "main-gate-exit"
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Index for common queries
            $table->index(['gate_name', 'gate_location']);
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gates');
    }
};
