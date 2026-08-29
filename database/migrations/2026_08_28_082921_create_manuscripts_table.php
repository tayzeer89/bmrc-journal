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
        Schema::create('manuscripts', function (Blueprint $table) {
                $table->id();

                $table->string('manuscript_id')->unique();

                $table->foreignId('submitted_by')
                    ->constrained('users')
                    ->restrictOnDelete();

               $table->foreignId('journal_id')
                    ->nullable()
                    ->constrained('journals')
                    ->onDelete('set null');

               $table->foreignId('article_type_id')
                    ->nullable()
                    ->constrained('article_types')
                    ->nullOnDelete();

                $table->string('title');

                $table->string('short_title')->nullable();

                $table->longText('abstract')->nullable();

                $table->json('keywords')->nullable();

                $table->string('subject_category')->nullable();
                $table->string('subcategory')->nullable();

                $table->string('language')->default('English');

                $table->unsignedInteger('word_count')->nullable();

                $table->unsignedInteger('number_of_tables')->default(0);
                $table->unsignedInteger('number_of_figures')->default(0);
                $table->unsignedInteger('number_of_references')->default(0);

                // Manuscript information
                $table->longText('background')->nullable();
                $table->longText('objective')->nullable();
                $table->longText('methods')->nullable();
                $table->longText('results')->nullable();
                $table->longText('conclusion')->nullable();

                $table->string('trial_registration_number')->nullable();
                $table->string('trial_registration_organization')->nullable();

                $table->string('study_design')->nullable();
                $table->date('study_start_date')->nullable();
                $table->date('study_end_date')->nullable();
                $table->text('study_location')->nullable();

                $table->unsignedInteger('sample_size')->nullable();

                // Status
                $table->string('status')->default('draft')->index();

                $table->string('submission_version')->default('1.0');

                $table->timestamp('submitted_at')->nullable();

                $table->timestamps();
                $table->softDeletes();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manuscripts');
    }
};
