<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviewer_invitations', function (Blueprint $table) {

            $table->id();


            /*
            |--------------------------------------------------------------------------
            | Manuscript
            |--------------------------------------------------------------------------
            */

            $table->foreignId('manuscript_id')
                ->constrained('manuscripts')
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Reviewer
            |--------------------------------------------------------------------------
            */

            $table->foreignId('reviewer_id')
                ->constrained('reviewers')
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Invited By
            |--------------------------------------------------------------------------
            |
            | Internal BMRC user / Handling Editor who sent
            | the reviewer invitation.
            |
            */

            $table->foreignId('invited_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Invitation Token
            |--------------------------------------------------------------------------
            |
            | Unique token used for the email invitation link.
            |
            */

            $table->string('invitation_token', 100)
                ->nullable()
                ->unique();


            /*
            |--------------------------------------------------------------------------
            | Invitation Status
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [
                'pending',
                'accepted',
                'declined',
                'expired',
                'cancelled',
            ])
                ->default('pending')
                ->index();


            /*
            |--------------------------------------------------------------------------
            | Invitation Date
            |--------------------------------------------------------------------------
            |
            | Date/time when the invitation was initially sent.
            |
            */

            $table->timestamp('invited_at')
                ->nullable()
                ->index();


            /*
            |--------------------------------------------------------------------------
            | Response Deadline
            |--------------------------------------------------------------------------
            |
            | Deadline for the reviewer to Accept or Decline
            | the invitation.
            |
            | Example:
            | invited_at + 3 days
            |
            */

            $table->timestamp('expires_at')
                ->nullable()
                ->index();


            /*
            |--------------------------------------------------------------------------
            | Review Deadline
            |--------------------------------------------------------------------------
            |
            | Deadline for completing/submitting the actual review.
            |
            | Current BMRC workflow:
            | invited_at + 15 days
            |
            */

            $table->timestamp('review_deadline')
                ->nullable()
                ->index();


            /*
            |--------------------------------------------------------------------------
            | Reviewer Response Date
            |--------------------------------------------------------------------------
            |
            | Date/time when reviewer accepted or declined.
            |
            */

            $table->timestamp('responded_at')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | Reminder Information
            |--------------------------------------------------------------------------
            */

            $table->unsignedInteger('reminder_count')
                ->default(0);

            $table->timestamp('last_reminder_at')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | Response / Decline Note
            |--------------------------------------------------------------------------
            |
            | Reviewer may provide a reason when declining
            | an invitation.
            |
            */

            $table->text('response_note')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | Laravel Timestamps
            |--------------------------------------------------------------------------
            */

            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index([
                'manuscript_id',
                'reviewer_id',
            ]);

            $table->index([
                'reviewer_id',
                'status',
            ]);

            $table->index([
                'manuscript_id',
                'status',
            ]);
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('reviewer_invitations');
    }
};