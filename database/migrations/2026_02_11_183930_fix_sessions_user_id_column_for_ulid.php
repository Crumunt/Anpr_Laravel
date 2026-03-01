<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sessions', function (Blueprint $table) {
            // Drop the existing index on user_id
            $table->dropIndex(['user_id']);
        });

        // Change user_id from bigint to char(26) for ULID compatibility
        DB::statement('ALTER TABLE sessions MODIFY user_id CHAR(26) NULL');

        Schema::table('sessions', function (Blueprint $table) {
            // Re-add the index
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sessions', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
        });

        DB::statement('ALTER TABLE sessions MODIFY user_id BIGINT UNSIGNED NULL');

        Schema::table('sessions', function (Blueprint $table) {
            $table->index('user_id');
        });
    }
};
