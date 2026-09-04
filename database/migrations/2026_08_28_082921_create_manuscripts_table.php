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

            /*
            |--------------------------------------------------------------------------
            | 1. Primary Identification
            |--------------------------------------------------------------------------
            */

            $table->id();

            $table->string('manuscript_id')
                ->unique()
                ->comment('BMRC Manuscript ID');


            /*
            |--------------------------------------------------------------------------
            | 2. Submission / Ownership
            |--------------------------------------------------------------------------
            */

            $table->foreignId('submitted_by')
                ->constrained('users')
                ->restrictOnDelete();


            /*
            |--------------------------------------------------------------------------
            | 3. Journal & Article Type
            |--------------------------------------------------------------------------
            */

            $table->foreignId('journal_id')
                ->nullable()
                ->constrained('journals')
                ->nullOnDelete();

            $table->foreignId('article_type_id')
                ->nullable()
                ->constrained('article_types')
                ->nullOnDelete();


            /*
            |--------------------------------------------------------------------------
            | 4. Article Information - Step 1
            |--------------------------------------------------------------------------
            */

            $table->string('title');

            $table->string('short_title')
                ->nullable();

            $table->longText('abstract')
                ->nullable();

            $table->json('keywords')
                ->nullable();

            $table->string('subject_category')
                ->nullable();

            $table->string('subcategory')
                ->nullable();

            $table->string('language')
                ->default('English');


            /*
            |--------------------------------------------------------------------------
            | 5. Manuscript Statistics - Step 1
            |--------------------------------------------------------------------------
            */

            $table->unsignedInteger('word_count')
                ->nullable();

            $table->unsignedInteger('number_of_tables')
                ->default(0);

            $table->unsignedInteger('number_of_figures')
                ->default(0);

            $table->unsignedInteger('number_of_references')
                ->default(0);


            /*
            |--------------------------------------------------------------------------
            | 6. Submission Status
            |--------------------------------------------------------------------------
            */

            $table->string('status')
                ->default('draft')
                ->index();

            /*
             | Examples:
             | draft
             | submitted
             | technical_check
             | technical_revision
             | editor_assigned
             | under_review
             | revision_required
             | accepted
             | rejected
             | copy_editing
             | proofreading
             | production
             | published
             */

       $table->string('current_stage')
                ->nullable()
                ->index();

            /*
            | Current Stage examples:
            |
            | submission
            | technical_review
            | author_correction
            | payment
            | payment_correction
            | editorial_assessment
            | similarity_check
            | peer_review
            | revision
            | decision
            | copy_editing
            | proofreading
            | production
            | publication
            */



            /*
            |--------------------------------------------------------------------------
            | 7. Submission Version
            |--------------------------------------------------------------------------
            */


            $table->string('submission_version')
                ->default('1.0');


            /*
            |--------------------------------------------------------------------------
            | 8. Draft Management
            |--------------------------------------------------------------------------
            */

            $table->unsignedTinyInteger('completion_percentage')
                ->default(0);

            $table->unsignedTinyInteger('last_step')
                ->default(1);

            $table->timestamp('draft_saved_at')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | 9. Final Submission
            |--------------------------------------------------------------------------
            */

            $table->timestamp('submitted_at')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | 10. Timestamps
            |--------------------------------------------------------------------------
            */

            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | 11. Soft Delete
            |--------------------------------------------------------------------------
            */

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