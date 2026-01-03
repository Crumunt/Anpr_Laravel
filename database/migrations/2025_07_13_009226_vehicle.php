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
        //
        Schema::create("vehicles", function (Blueprint $table) {
            $table->ulid("id")->primary();
            $table->foreignUlid('application_id')->constrained('applications')->cascadeOnDelete();
            $table
                ->foreignUlid("owner_id")
                ->constrained("users")
                ->cascadeOnDelete();
            $table->string("plate_number")->nullable();
            $table->string("type");
            $table->string("make");
            $table->string("model");
            $table->integer("year");
            $table->string("color")->nullable();
            $table->string("assigned_gate_pass");
            $table->foreignId("status_id")->references("id")->on("statuses");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists("vehicle");
    }
};
