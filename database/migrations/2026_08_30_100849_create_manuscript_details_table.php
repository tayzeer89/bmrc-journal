<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manuscript_details', function (Blueprint $table) {

            $table->id();


            /*
            |--------------------------------------------------------------------------
            | Manuscript Relationship
            |--------------------------------------------------------------------------
            */

            $table->foreignId('manuscript_id')
                ->unique()
                ->constrained('manuscripts')
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | 1. Scientific Information
            |--------------------------------------------------------------------------
            */

            $table->longText('background')
                ->nullable();

            $table->longText('objective')
                ->nullable();

            $table->longText('methods')
                ->nullable();

            $table->longText('results')
                ->nullable();

            $table->longText('conclusion')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | 2. Trial Registration
            |--------------------------------------------------------------------------
            */

            $table->string('trial_registration_number')
                ->nullable();

            $table->string('trial_registration_organization')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | 3. Study Design
            |--------------------------------------------------------------------------
            */

            $table->string('study_design')
                ->nullable();

            $table->string('other_study_design')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | 4. Study Period
            |--------------------------------------------------------------------------
            */

            $table->date('study_start_date')
                ->nullable();

            $table->date('study_end_date')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | 5. Study Information
            |--------------------------------------------------------------------------
            */

            $table->text('study_location')
                ->nullable();

            $table->unsignedInteger('sample_size')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | 6. Funding
            |--------------------------------------------------------------------------
            */

            $table->string('funding_source')
                ->nullable();

            $table->string('other_funding_source')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | 7. Ethical Approval
            |--------------------------------------------------------------------------
            */

            $table->boolean('ethical_approval_available')
                ->default(false);

            $table->string('ethical_approval_number')
                ->nullable();

            $table->date('ethical_approval_date')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | Timestamps
            |--------------------------------------------------------------------------
            */

            $table->timestamps();

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('manuscript_details');
    }
};