<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
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
            |
            | IMPORTANT:
            | Keep users if your author submission currently references users.
            | If authors authenticate only through authors table, change to authors.
            |
            */

            $table->foreignId('submitted_by')
                ->constrained('users')
                ->restrictOnDelete();


            /*
            |--------------------------------------------------------------------------
            | 3. Journal
            |--------------------------------------------------------------------------
            */

            $table->foreignId('journal_id')
                ->nullable()
                ->constrained('journals')
                ->nullOnDelete();


            /*
            |--------------------------------------------------------------------------
            | 4. Article Type
            |--------------------------------------------------------------------------
            */

            $table->foreignId('article_type_id')
                ->nullable()
                ->constrained('article_types')
                ->nullOnDelete();


            /*
            |--------------------------------------------------------------------------
            | 5. Article Information
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
            | 6. Manuscript Statistics
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
            | 7. Workflow Status
            |--------------------------------------------------------------------------
            */

            $table->string('status')
                ->default('draft')
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Recommended status values
            |--------------------------------------------------------------------------
            |
            | draft
            | submitted
            |
            | technical_check
            | technical_revision
            | technical_passed
            |
            | payment_required
            | payment_correction
            | payment_verified
            |
            | similarity_check
            | similarity_completed
            |
            | editor_assignment
            | editor_assigned
            | editorial_assessment
            |
            | reviewer_selection
            | reviewer_invitation
            | under_review
            | reviews_completed
            |
            | editor_recommendation
            | eic_decision
            |
            | minor_revision
            | major_revision
            | revision_submitted
            |
            | accepted
            | rejected
            |
            | copy_editing
            | proofreading
            | production
            | publication_ready
            | published
            |
            */


            /*
            |--------------------------------------------------------------------------
            | 8. Broad Current Stage
            |--------------------------------------------------------------------------
            */

            $table->string('current_stage')
                ->default('submission')
                ->index();

            /*
            |--------------------------------------------------------------------------
            | current_stage values
            |--------------------------------------------------------------------------
            |
            | submission
            | technical_review
            | payment
            | similarity_check
            | editorial
            | peer_review
            | revision
            | decision
            | copy_editing
            | proofreading
            | production
            | publication
            |
            */


            /*
            |--------------------------------------------------------------------------
            | 9. Current Handling Editor
            |--------------------------------------------------------------------------
            |
            | This is ONLY the current editor.
            |
            | Assignment history is stored separately in:
            | editor_assignments
            |
            */

            $table->foreignId('handling_editor_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();


            /*
            |--------------------------------------------------------------------------
            | 10. Review / Revision Tracking
            |--------------------------------------------------------------------------
            */

            $table->unsignedInteger('review_round')
                ->default(0);

            $table->unsignedInteger('revision_round')
                ->default(0);


            /*
            |--------------------------------------------------------------------------
            | 11. Submission Version
            |--------------------------------------------------------------------------
            */

            $table->string('submission_version')
                ->default('1.0');


            /*
            |--------------------------------------------------------------------------
            | 12. Draft Management
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
            | 13. Submission Date
            |--------------------------------------------------------------------------
            */

            $table->timestamp('submitted_at')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | 14. Editorial Dates
            |--------------------------------------------------------------------------
            */

            $table->timestamp('accepted_at')
                ->nullable();

            $table->timestamp('rejected_at')
                ->nullable();

            $table->timestamp('published_at')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | 15. Timestamps
            |--------------------------------------------------------------------------
            */

            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | 16. Soft Delete
            |--------------------------------------------------------------------------
            */

            $table->softDeletes();


            /*
            |--------------------------------------------------------------------------
            | 17. Workflow Indexes
            |--------------------------------------------------------------------------
            */

            $table->index([
                'status',
                'current_stage'
            ]);

            $table->index([
                'handling_editor_id',
                'status'
            ]);

            $table->index([
                'journal_id',
                'status'
            ]);
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('manuscripts');
    }
};