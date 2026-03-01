<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds the is_flagged column to support real flagging functionality.
     * Also adds additional indexes for dashboard query optimization.
     */
    public function up(): void
    {
        Schema::table('records', function (Blueprint $table) {
            // Add flagged status column
            $table->boolean('is_flagged')->default(false)->after('detected_at');

            // Add index for flagged records queries
            $table->index('is_flagged');

            // Add composite index for flagged + time queries
            $table->index(['is_flagged', 'detected_at']);

            // Add index for created_at if not exists (for 24-hour queries)
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('records', function (Blueprint $table) {
            $table->dropIndex(['is_flagged']);
            $table->dropIndex(['is_flagged', 'detected_at']);
            $table->dropIndex(['created_at']);
            $table->dropColumn('is_flagged');
        });
    }
};
