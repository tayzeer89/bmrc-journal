<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('technical_checks', function (Blueprint $table) {

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
            | Check Information
            |--------------------------------------------------------------------------
            */

            $table->unsignedInteger('check_number')
                ->default(1);

            $table->enum('status', [
                'pending',
                'in_progress',
                'correction_required',
                'passed',
                'failed',
            ])
                ->default('pending')
                ->index();

            $table->enum('overall_result', [
                'passed',
                'passed_with_minor_corrections',
                'correction_required',
                'failed',
            ])
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | Users
            |--------------------------------------------------------------------------
            */

            $table->foreignId('assigned_to')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('started_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('completed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Dates
            |--------------------------------------------------------------------------
            */

            $table->timestamp('started_at')
                ->nullable();

            $table->timestamp('completed_at')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | Comments
            |--------------------------------------------------------------------------
            */

            $table->text('comments')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | Timestamps
            |--------------------------------------------------------------------------
            */

            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | Prevent Duplicate Check Number
            |--------------------------------------------------------------------------
            |
            | Example:
            |
            | Manuscript #101
            |   Check #1
            |   Check #2
            |   Check #3
            |
            */

            $table->unique([
                'manuscript_id',
                'check_number',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('technical_checks');
    }
};