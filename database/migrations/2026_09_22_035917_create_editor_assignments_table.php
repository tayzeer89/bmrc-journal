<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('editor_assignments', function (Blueprint $table) {

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
            | Handling Editor
            |--------------------------------------------------------------------------
            */

            $table->foreignId('editor_id')
                ->constrained('users')
                ->restrictOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Assigned By
            |--------------------------------------------------------------------------
            |
            | Normally Editor-in-Chief
            |
            */

            $table->foreignId('assigned_by')
                ->constrained('users')
                ->restrictOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Assignment Round
            |--------------------------------------------------------------------------
            */

            $table->unsignedInteger('assignment_round')
                ->default(1);


            /*
            |--------------------------------------------------------------------------
            | Assignment Status
            |--------------------------------------------------------------------------
            */

            $table->string('status')
                ->default('pending')
                ->index();

            /*
             | pending
             | accepted
             | declined
             | completed
             | cancelled
             | reassigned
             */


            /*
            |--------------------------------------------------------------------------
            | Assignment Information
            |--------------------------------------------------------------------------
            */

            $table->text('assignment_note')
                ->nullable();

            $table->date('due_date')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | Handling Editor Response
            |--------------------------------------------------------------------------
            */

            $table->text('decline_reason')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | Important Dates
            |--------------------------------------------------------------------------
            */

            $table->timestamp('assigned_at')
                ->nullable();

            $table->timestamp('accepted_at')
                ->nullable();

            $table->timestamp('declined_at')
                ->nullable();

            $table->timestamp('completed_at')
                ->nullable();

            $table->timestamp('cancelled_at')
                ->nullable();


            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index([
                'manuscript_id',
                'status'
            ]);

            $table->index([
                'editor_id',
                'status'
            ]);

            $table->index([
                'assigned_by',
                'status'
            ]);
        });
    }


    public function down(): void
    {
        Schema::dropIfExists(
            'editor_assignments'
        );
    }
};