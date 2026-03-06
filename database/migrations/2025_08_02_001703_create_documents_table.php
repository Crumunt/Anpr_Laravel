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
        Schema::create("documents", function (Blueprint $table) {
            $table->ulid("id")->primary();
            $table->foreignUlid("application_id")->constrained()->cascadeOnDelete();
            $table->foreignUlid('vehicle_id')->nullable()->constrained('vehicles')->nullOnDelete();
            $table->enum("type", [
                "vehicle_registration",
                "license",
                "proof_of_identification",
            ]);
            $table->string("file_path");
            $table->string('mime_type')->nullable();
            $table->integer('file_size')->nullable();
            $table->foreignId("status_id")->constrained("statuses");
            $table->text("rejection_reason")->nullable();
            $table->foreignUlid("reviewed_by")->nullable()->constrained("users");
            $table->timestamp("reviewed_at")->nullable();
            $table->integer("version")->default(1);
            $table->foreignUlid("replaced_by")->nullable()->constrained("documents");
            $table->boolean("is_current")->default(true);
            $table->boolean('is_renewal_document')->default(false);
            $table->timestamps();

            $table->index(["application_id", "is_current"]);
            $table->index("type");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("documents");
    }
};
