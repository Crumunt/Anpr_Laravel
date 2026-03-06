<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("vehicles", function (Blueprint $table) {
            $table->ulid("id")->primary();
            $table->foreignUlid('application_id')->constrained('applications')->cascadeOnDelete();
            $table->foreignUlid("owner_id")->constrained("users")->cascadeOnDelete();
            $table->string("plate_number")->nullable();
            $table->string("type");
            $table->string("make");
            $table->string("model");
            $table->integer("year");
            $table->string("color")->nullable();
            $table->string("assigned_gate_pass")->nullable();
            $table->string('previous_gate_pass')->nullable();
            $table->unsignedInteger('gate_pass_assignment_count')->default(0);
            $table->integer('validity_years')->default(4);
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_renewal')->default(false);
            $table->ulid('renewed_from_vehicle_id')->nullable();
            $table->foreign('renewed_from_vehicle_id')->references('id')->on('vehicles')->nullOnDelete();
            $table->timestamp('renewal_requested_at')->nullable();
            $table->boolean('has_pending_renewal')->default(false);
            $table->foreignUlid('pending_renewal_application_id')->nullable()->constrained('applications')->nullOnDelete();
            $table->foreignId("status_id")->references("id")->on("statuses");
            $table->timestamps();

            $table->index('expires_at');
            $table->index(['owner_id', 'expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("vehicles");
    }
};
