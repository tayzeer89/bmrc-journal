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
        Schema::create('proofs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('manuscript_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('proof_version');

            $table->foreignId('proof_file_id')
                ->nullable()
                ->constrained('manuscript_files')
                ->nullOnDelete();

            $table->timestamp('proof_date')->nullable();

            $table->boolean('correction_required')->default(false);

            $table->text('correction_comments')->nullable();

            $table->boolean('author_approved')->default(false);

            $table->timestamp('approval_date')->nullable();

            $table->boolean('digital_confirmation')->default(false);

            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proofs');
    }
};
