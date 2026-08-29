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
        Schema::create('reviewer_profiles', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Primary Key
            |--------------------------------------------------------------------------
            */

            $table->id();


            /*
            |--------------------------------------------------------------------------
            | REVIEWER ACCOUNT
            |--------------------------------------------------------------------------
            |
            | This connects the profile to the separate reviewers table.
            |
            | reviewers.id
            |       ↓
            | reviewer_profiles.reviewer_id
            |
            */

            $table->foreignId('reviewer_id')
                ->unique()
                ->constrained('reviewers')
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | APPLICATION IDENTIFICATION
            |--------------------------------------------------------------------------
            */

            $table->string('application_id')
                ->unique()
                ->nullable();

            $table->string('reviewer_code')
                ->unique()
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | PERSONAL INFORMATION
            |--------------------------------------------------------------------------
            */

            $table->string('title')
                ->nullable();

            $table->string('first_name');

            $table->string('middle_name')
                ->nullable();

            $table->string('last_name');

            $table->string('display_name');


            /*
            |--------------------------------------------------------------------------
            | CONTACT INFORMATION
            |--------------------------------------------------------------------------
            */

            $table->string('alternative_email')
                ->nullable();

            $table->string('mobile');


            /*
            |--------------------------------------------------------------------------
            | PERSONAL DETAILS
            |--------------------------------------------------------------------------
            */

            $table->string('gender')
                ->nullable();

            $table->date('date_of_birth')
                ->nullable();

            $table->string('nationality')
                ->nullable();

            $table->string('country')
                ->default('Bangladesh');

            $table->string('division_state')
                ->nullable();

            $table->string('city_district')
                ->nullable();

            $table->text('postal_address')
                ->nullable();

            $table->text('office_address')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | PROFESSIONAL INFORMATION
            |--------------------------------------------------------------------------
            */

            $table->string('institution')
                ->nullable();

            $table->string('department')
                ->nullable();

            $table->string('designation')
                ->nullable();

            $table->text('academic_degree')
                ->nullable();

            $table->text('highest_degree')
                ->nullable();

            $table->string('degree_institution')
                ->nullable();

            $table->year('year_of_highest_degree')
                ->nullable();

            $table->text('specialization')
                ->nullable();

            $table->text('professional_experience')
                ->nullable();

            $table->unsignedTinyInteger('years_of_experience')
                ->nullable();

            $table->string('professional_registration_no')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | RESEARCH INFORMATION
            |--------------------------------------------------------------------------
            */

            $table->text('research_interest')
                ->nullable();

            $table->unsignedInteger('publication_count')
                ->nullable();

            $table->unsignedInteger('first_author_publications')
                ->nullable();

            $table->unsignedInteger('corresponding_author_publications')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | RESEARCH IDENTIFIERS
            |--------------------------------------------------------------------------
            */

            $table->string('orcid')
                ->nullable()
                ->index();

            $table->string('researcher_id')
                ->nullable();

            $table->string('scopus_author_id')
                ->nullable();

            $table->string('web_of_science_id')
                ->nullable();

            $table->text('google_scholar_profile')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | REVIEWER EXPERTISE
            |--------------------------------------------------------------------------
            */

            $table->text('reviewer_expertise')
                ->nullable();

            $table->text('primary_expertise')
                ->nullable();

            $table->text('secondary_expertise')
                ->nullable();

            $table->text('methodological_expertise')
                ->nullable();

            $table->text('keywords')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | REVIEWING EXPERIENCE
            |--------------------------------------------------------------------------
            */

            $table->text('reviewing_experience')
                ->nullable();

            $table->unsignedInteger('number_of_reviews_completed')
                ->nullable();

            $table->text('previous_journal_experience')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | CURRICULUM VITAE
            |--------------------------------------------------------------------------
            */

            $table->string('cv_file')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | REVIEWER APPLICATION STATUS
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'suspended'
            ])->default('pending');


            /*
            |--------------------------------------------------------------------------
            | APPLICATION DATES
            |--------------------------------------------------------------------------
            */

            $table->timestamp('applied_at')
                ->nullable();

            $table->timestamp('approved_at')
                ->nullable();

            $table->timestamp('rejected_at')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | APPROVAL INFORMATION
            |--------------------------------------------------------------------------
            |
            | approved_by refers to users table because the BMRC
            | Editorial/Admin user approves the reviewer.
            |
            */

            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('approval_note')
                ->nullable();

            $table->text('rejection_reason')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | PROFILE COMPLETION
            |--------------------------------------------------------------------------
            */

            $table->boolean('profile_completed')
                ->default(false);


            /*
            |--------------------------------------------------------------------------
            | REVIEWER AVAILABILITY
            |--------------------------------------------------------------------------
            */

            $table->boolean('available_for_review')
                ->default(false);


            /*
            |--------------------------------------------------------------------------
            | COMMUNICATION PREFERENCE
            |--------------------------------------------------------------------------
            */

            $table->string('preferred_communication_method')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | PAYMENT INFORMATION
            |--------------------------------------------------------------------------
            */

            $table->string('payment_method')
                ->nullable();

            $table->string('payment_account')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | TAX INFORMATION
            |--------------------------------------------------------------------------
            */

            $table->string('taxpayer_type')
                ->nullable();

            $table->string('tin_number')
                ->nullable();

            $table->string('nid_number')
                ->nullable();

            $table->string('bin_number')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | DECLARATIONS
            |--------------------------------------------------------------------------
            */

            $table->boolean('conflict_of_interest_declaration')
                ->default(false);

            $table->boolean('reviewer_ethics_declaration')
                ->default(false);


            /*
            |--------------------------------------------------------------------------
            | TIMESTAMPS
            |--------------------------------------------------------------------------
            */

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviewer_profiles');
    }
};