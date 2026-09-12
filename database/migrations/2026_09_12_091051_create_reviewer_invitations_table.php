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
            | Internal staff / editor from users table.
            |
            */

            $table->foreignId('invited_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Invitation Information
            |--------------------------------------------------------------------------
            */

            $table->string('invitation_token')
                ->nullable()
                ->unique();


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
            | Invitation Dates
            |--------------------------------------------------------------------------
            */

            $table->timestamp('invited_at')
                ->nullable();

            $table->timestamp('responded_at')
                ->nullable();

            $table->timestamp('expires_at')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | Reminder
            |--------------------------------------------------------------------------
            */

            $table->unsignedInteger('reminder_count')
                ->default(0);

            $table->timestamp('last_reminder_at')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | Response / Notes
            |--------------------------------------------------------------------------
            */

            $table->text('response_note')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | Timestamps
            |--------------------------------------------------------------------------
            */

            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | Useful Index
            |--------------------------------------------------------------------------
            */

            $table->index([
                'manuscript_id',
                'reviewer_id',
            ]);
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('reviewer_invitations');
    }
};