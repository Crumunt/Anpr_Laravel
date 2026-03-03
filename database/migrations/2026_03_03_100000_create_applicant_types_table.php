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
        Schema::create('applicant_types', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name')->unique(); // e.g., 'student', 'faculty', 'staff'
            $table->string('label'); // Display label e.g., 'Student', 'Faculty Member'
            $table->text('description')->nullable();
            $table->boolean('requires_clsu_id')->default(true);
            $table->boolean('requires_department')->default(true);
            $table->boolean('requires_position')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('applicant_type_documents', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('applicant_type_id')->constrained('applicant_types')->cascadeOnDelete();
            $table->string('name'); // Internal name e.g., 'vehicle_registration'
            $table->string('label'); // Display label e.g., 'Vehicle Registration'
            $table->text('description')->nullable(); // Help text for users
            $table->string('accepted_formats')->default('pdf,jpg,jpeg,png'); // Comma-separated
            $table->integer('max_file_size')->default(10240); // In KB
            $table->boolean('is_required')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['applicant_type_id', 'name']);
        });

        // Update applications table to use foreign key instead of enum
        Schema::table('applications', function (Blueprint $table) {
            // Add new column for foreign key relationship
            $table->foreignUlid('applicant_type_id')
                ->nullable()
                ->after('applicant_type')
                ->constrained('applicant_types')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropConstrainedForeignId('applicant_type_id');
        });

        Schema::dropIfExists('applicant_type_documents');
        Schema::dropIfExists('applicant_types');
    }
};
