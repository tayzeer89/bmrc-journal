<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create technical correction responses table.
     *
     * This table stores the author's response to a failed
     * technical check.
     */
    public function up(): void
    {
        Schema::create('technical_correction_responses', function (Blueprint $table) {

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
            | Technical Check
            |--------------------------------------------------------------------------
            |
            | This identifies which technical check the author is responding to.
            |
            */

            $table->foreignId('technical_check_id')
                ->constrained('technical_checks')
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Version
            |--------------------------------------------------------------------------
            |
            | The corrected submission/version created by the author.
            |
            */

            $table->foreignId('manuscript_version_id')
                ->nullable()
                ->constrained('manuscript_versions')
                ->nullOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Author
            |--------------------------------------------------------------------------
            */

            $table->foreignId('submitted_by')
                ->constrained('users')
                ->restrictOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Author Response
            |--------------------------------------------------------------------------
            */

            $table->text('response')->nullable();


            /*
            |--------------------------------------------------------------------------
            | Submission Time
            |--------------------------------------------------------------------------
            */

            $table->timestamp('submitted_at')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | Timestamps
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
                'technical_check_id',
            ]);
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('technical_correction_responses');
    }
};