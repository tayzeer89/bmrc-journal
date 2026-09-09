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

            $table->unsignedBigInteger('manuscript_id');

            $table->foreign(
                'manuscript_id',
                'tcr_manuscript_fk'
            )
                ->references('id')
                ->on('manuscripts')
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Technical Check
            |--------------------------------------------------------------------------
            |
            | This identifies which technical check the author is responding to.
            |
            */

            $table->unsignedBigInteger('technical_check_id');

            $table->foreign(
                'technical_check_id',
                'tcr_technical_check_fk'
            )
                ->references('id')
                ->on('technical_checks')
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Version
            |--------------------------------------------------------------------------
            |
            | The corrected submission/version created by the author.
            |
            */

            $table->unsignedBigInteger('manuscript_version_id')
                ->nullable();

            $table->foreign(
                'manuscript_version_id',
                'tcr_manuscript_version_fk'
            )
                ->references('id')
                ->on('manuscript_versions')
                ->nullOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Author
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger('submitted_by');

            $table->foreign(
                'submitted_by',
                'tcr_submitted_by_fk'
            )
                ->references('id')
                ->on('users')
                ->restrictOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Author Response
            |--------------------------------------------------------------------------
            */

            $table->text('response')
                ->nullable();


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

            $table->index(
                [
                    'manuscript_id',
                    'technical_check_id',
                ],
                'tcr_manuscript_check_idx'
            );
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