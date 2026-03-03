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
            // Add gate_id foreign key column
            $table->foreignId('gate_id')
                ->nullable()
                ->after('gate_type')
                ->constrained('gates')
                ->nullOnDelete();

            // Index for filtering by gate
            $table->index(['gate_id', 'detected_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('records', function (Blueprint $table) {
            $table->dropForeign(['gate_id']);
            $table->dropIndex(['gate_id', 'detected_at']);
            $table->dropColumn('gate_id');
        });
    }
};
