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
        Schema::table('records', function (Blueprint $table) {
            // Gate pass information - null if vehicle not registered
            $table->string('gate_pass_number', 50)->nullable()->after('is_flagged');
            $table->uuid('vehicle_id')->nullable()->after('gate_pass_number');

            // Index for quick lookups
            $table->index('gate_pass_number');
            $table->index('vehicle_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('records', function (Blueprint $table) {
            $table->dropIndex(['gate_pass_number']);
            $table->dropIndex(['vehicle_id']);
            $table->dropColumn(['gate_pass_number', 'vehicle_id']);
        });
    }
};
