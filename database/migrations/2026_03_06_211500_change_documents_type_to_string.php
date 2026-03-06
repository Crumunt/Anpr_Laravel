<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Changes the `type` column from ENUM to VARCHAR to support
     * dynamic document types from the applicant_type_documents table.
     */
    public function up(): void
    {
        // MySQL requires dropping and recreating the column to change from ENUM to VARCHAR
        DB::statement("ALTER TABLE documents MODIFY COLUMN type VARCHAR(100) NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: This will fail if there are values not in the enum
        DB::statement("ALTER TABLE documents MODIFY COLUMN type ENUM('vehicle_registration', 'license', 'proof_of_identification') NOT NULL");
    }
};
