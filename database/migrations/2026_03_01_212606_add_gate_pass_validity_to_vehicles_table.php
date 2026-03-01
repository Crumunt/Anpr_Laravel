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
            // Gate pass validity columns
            $table->integer('validity_years')->default(4)->after('assigned_gate_pass');
            $table->timestamp('approved_at')->nullable()->after('validity_years');
            $table->timestamp('expires_at')->nullable()->after('approved_at');

            // Renewal tracking columns
            $table->boolean('is_renewal')->default(false)->after('expires_at');
            $table->ulid('renewed_from_vehicle_id')->nullable()->after('is_renewal');
            $table->foreign('renewed_from_vehicle_id')->references('id')->on('vehicles')->nullOnDelete();
            $table->timestamp('renewal_requested_at')->nullable()->after('renewed_from_vehicle_id');

            // Index for expiration queries
            $table->index('expires_at');
            $table->index(['owner_id', 'expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropForeign(['renewed_from_vehicle_id']);
            $table->dropIndex(['expires_at']);
            $table->dropIndex(['owner_id', 'expires_at']);
            $table->dropColumn([
                'validity_years',
                'approved_at',
                'expires_at',
                'is_renewal',
                'renewed_from_vehicle_id',
                'renewal_requested_at',
            ]);
        });
    }
};
