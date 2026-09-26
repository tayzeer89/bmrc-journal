<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peer_reviews', function (Blueprint $table) {

            $table->id();

            $table->foreignId('manuscript_id')
                ->constrained('manuscripts')
                ->cascadeOnDelete();

            $table->foreignId('reviewer_id')
                ->constrained('reviewers')
                ->cascadeOnDelete();

            $table->foreignId('reviewer_invitation_id')
                ->constrained('reviewer_invitations')
                ->cascadeOnDelete();

            $table->unsignedInteger('review_round')
                ->default(1);

            $table->enum('status', [
                'draft',
                'submitted',
            ])->default('draft');

            /*
            |--------------------------------------------------------------------------
            | Final BMRC Evaluation
            |--------------------------------------------------------------------------
            */

            $table->enum('overall_evaluation', [
                'suitable',
                'major_revision',
                'minor_revision',
                'not_suitable',
            ])->nullable();

            /*
            |--------------------------------------------------------------------------
            | General Comments
            |--------------------------------------------------------------------------
            */

            $table->longText('comments_to_author')
                ->nullable();

            $table->longText('confidential_comments_to_editor')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Reviewer Declaration
            |--------------------------------------------------------------------------
            */

            $table->boolean('conflict_of_interest')
                ->default(false);

            $table->text('conflict_details')
                ->nullable();

            $table->boolean('confidentiality_confirmed')
                ->default(false);

            $table->boolean('reviewer_declaration')
                ->default(false);

            /*
            |--------------------------------------------------------------------------
            | Dates
            |--------------------------------------------------------------------------
            */

            $table->timestamp('started_at')
                ->nullable();

            $table->timestamp('submitted_at')
                ->nullable();

            $table->timestamps();

            $table->unique(
                [
                    'reviewer_invitation_id',
                    'review_round',
                ],
                'peer_review_invitation_round_unique'
            );

            $table->index([
                'manuscript_id',
                'status',
            ]);
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('peer_reviews');
    }
};