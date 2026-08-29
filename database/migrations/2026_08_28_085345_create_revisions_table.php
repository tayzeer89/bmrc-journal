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
        Schema::create('revisions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('manuscript_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('revision_id')->unique();

            $table->unsignedInteger('revision_number');

            $table->string('previous_version')->nullable();

            $table->string('revision_type');

            $table->date('deadline')->nullable();

            $table->text('author_comments')->nullable();

            $table->timestamp('submitted_at')->nullable();

            $table->foreignId('submitted_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('status')->default('draft');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revisions');
    }
};
