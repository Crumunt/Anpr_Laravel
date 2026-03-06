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
        Schema::create('records', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('plate_number', 20)->index();
            $table->decimal('confidence', 5, 4);

            // Bounding box coordinates
            $table->decimal('bbox_x1', 10, 2)->nullable();
            $table->decimal('bbox_y1', 10, 2)->nullable();
            $table->decimal('bbox_x2', 10, 2)->nullable();
            $table->decimal('bbox_y2', 10, 2)->nullable();

            // Gate/Camera identification
            $table->string('camera_id', 50)->nullable()->index();
            $table->string('gate_type', 20)->nullable()->index();
            $table->foreignId('gate_id')->nullable()->constrained('gates')->nullOnDelete();
            $table->string('location', 100)->nullable();

            // Detection timestamp
            $table->timestamp('detected_at');

            // Flagged status
            $table->boolean('is_flagged')->default(false);

            // Gate pass information - null if vehicle not registered
            $table->string('gate_pass_number', 50)->nullable();
            $table->uuid('vehicle_id')->nullable();

            $table->timestamps();

            // Composite indexes
            $table->index(['plate_number', 'detected_at']);
            $table->index(['gate_type', 'detected_at']);
            $table->index(['camera_id', 'detected_at']);
            $table->index(['gate_id', 'detected_at']);
            $table->index('is_flagged');
            $table->index(['is_flagged', 'detected_at']);
            $table->index('created_at');
            $table->index('gate_pass_number');
            $table->index('vehicle_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('records');
    }
};
