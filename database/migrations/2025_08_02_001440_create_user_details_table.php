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
        Schema::create('user_details', function (Blueprint $table) {
            $table->uuid('user_id')->primary();
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->cascadeOnDelete();
            

            $table->string('clsu_id');
            $table->string('current_address');
            $table->string('street_address');
            $table->string('barangay');
            $table->string('city_municipality');
            $table->string('province');
            $table->string('postal_code');
            $table->string('country');
            $table->string('license_number');
            $table->string('college_unit_department');
            $table->string('phone_number');

            // admin approved by
            $table->uuid('approved_by')->nullable();
            $table->foreign('approved_by')
                  ->references('id')
                  ->on('users')
                  ->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_details');
    }
};
