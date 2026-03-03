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
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('previous_gate_pass')->nullable()->after('assigned_gate_pass');
            $table->unsignedInteger('gate_pass_assignment_count')->default(0)->after('previous_gate_pass');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn(['previous_gate_pass', 'gate_pass_assignment_count']);
        });
    }
};
