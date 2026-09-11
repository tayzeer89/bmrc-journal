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
            | PRIMARY KEY
            |--------------------------------------------------------------------------
            */

            $table->id();


            /*
            |--------------------------------------------------------------------------
            | REVIEWER ACCOUNT
            |--------------------------------------------------------------------------
            |
            | One reviewer can have only one reviewer profile.
            |
            | reviewers.id
            |      ↓
            | reviewer_profiles.reviewer_id
            |
            */

            $table->foreignId('reviewer_id')
                ->unique()
                ->constrained('reviewers')
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | REVIEWER IDENTIFICATION
            |--------------------------------------------------------------------------
            |
            | application_id:
            | Generated when the reviewer profile/application is created.
            |
            | reviewer_code:
            | Permanent reviewer identification code after approval.
            |
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

            $table->string('title', 30)
                ->nullable();

            $table->string('first_name');

            $table->string('middle_name')
                ->nullable();

            $table->string('last_name');

            $table->string('display_name')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | CONTACT INFORMATION
            |--------------------------------------------------------------------------
            |
            | Primary email remains in reviewers.email.
            |
            */

            $table->string('alternative_email')
                ->nullable();

            $table->string('mobile', 30)
                ->nullable();

            $table->string('alternative_mobile', 30)
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | PERSONAL / LOCATION INFORMATION
            |--------------------------------------------------------------------------
            */

            $table->string('gender', 30)
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

            $table->string('postal_code', 30)
                ->nullable();

            $table->text('postal_address')
                ->nullable();

            $table->text('office_address')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | CURRENT PROFESSIONAL INFORMATION
            |--------------------------------------------------------------------------
            */

            $table->string('institution')
                ->nullable()
                ->index();

            $table->string('department')
                ->nullable();

            $table->string('designation')
                ->nullable();

            $table->string('organization_type')
                ->nullable();

            $table->text('professional_experience')
                ->nullable();

            $table->unsignedSmallInteger('years_of_experience')
                ->nullable();

            $table->string('professional_registration_no')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | ACADEMIC / PROFESSIONAL QUALIFICATIONS
            |--------------------------------------------------------------------------
            |
            | These fields can hold summary information.
            | A separate reviewer_qualifications table can be introduced later
            | if multiple structured qualifications are required.
            |
            */

            $table->text('academic_qualifications')
                ->nullable();

            $table->text('professional_qualifications')
                ->nullable();

            $table->string('highest_degree')
                ->nullable();

            $table->string('highest_degree_institution')
                ->nullable();

            $table->year('year_of_highest_degree')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | SPECIALITY
            |--------------------------------------------------------------------------
            |
            | Used during reviewer searching and selection.
            |
            */

            $table->string('speciality')
                ->nullable()
                ->index();

            $table->string('sub_speciality')
                ->nullable()
                ->index();

            $table->text('specialization')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | RESEARCH INFORMATION
            |--------------------------------------------------------------------------
            */

            $table->text('research_interests')
                ->nullable();

            $table->unsignedInteger('publication_count')
                ->nullable();

            $table->unsignedInteger('first_author_publications')
                ->nullable();

            $table->unsignedInteger('corresponding_author_publications')
                ->nullable();

            $table->text('research_experience')
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
            |
            | These fields support initial reviewer searching.
            |
            | For advanced filtering, separate relational tables can later
            | be used for expertise and keywords.
            |
            */

            $table->text('areas_of_expertise')
                ->nullable();

            $table->string('primary_expertise')
                ->nullable()
                ->index();

            $table->text('secondary_expertise')
                ->nullable();

            $table->text('methodological_expertise')
                ->nullable();

            $table->text('expertise_keywords')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | REVIEWING EXPERIENCE
            |--------------------------------------------------------------------------
            |
            | Do not use this section as the authoritative source for BMRC
            | manuscript review statistics. Those should later come from
            | reviewer assignment/review tables.
            |
            */

            $table->text('reviewing_experience')
                ->nullable();

            $table->text('previous_journal_experience')
                ->nullable();

            $table->unsignedInteger('external_reviews_completed')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | PROFESSIONAL MEMBERSHIPS
            |--------------------------------------------------------------------------
            */

            $table->text('professional_memberships')
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
            | PROFILE COMPLETION
            |--------------------------------------------------------------------------
            |
            | false = reviewer has not completed all required information
            | true  = profile has been completed
            |
            */

            $table->boolean('profile_completed')
                ->default(false);

            $table->unsignedTinyInteger('profile_completion_percentage')
                ->default(0);

            $table->timestamp('profile_completed_at')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | REVIEWER APPROVAL STATUS
            |--------------------------------------------------------------------------
            |
            | draft
            |     Reviewer has not submitted the profile.
            |
            | pending_approval
            |     Reviewer completed and submitted the profile.
            |
            | update_requested
            |     EIC / authorized authority requested corrections.
            |
            | approved
            |     Reviewer is approved and available for reviewer pool,
            |     subject to availability.
            |
            | rejected
            |     Reviewer application was rejected.
            |
            */

            $table->enum('approval_status', [
                'draft',
                'pending_approval',
                'update_requested',
                'approved',
                'rejected',
            ])->default('draft')->index();


            /*
            |--------------------------------------------------------------------------
            | APPLICATION / APPROVAL DATES
            |--------------------------------------------------------------------------
            */

            $table->timestamp('submitted_for_approval_at')
                ->nullable();

            $table->timestamp('approved_at')
                ->nullable();

            $table->timestamp('rejected_at')
                ->nullable();

            $table->timestamp('update_requested_at')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | APPROVAL INFORMATION
            |--------------------------------------------------------------------------
            |
            | approved_by refers to the users table because Editor-in-Chief
            | or another authorized editorial authority approves reviewers.
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

            $table->text('profile_update_request')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | REVIEWER AVAILABILITY
            |--------------------------------------------------------------------------
            |
            | Reviewer may temporarily make themselves unavailable without
            | losing approved reviewer status.
            |
            */

            $table->boolean('available_for_review')
                ->default(false)
                ->index();

            $table->date('unavailable_from')
                ->nullable();

            $table->date('unavailable_until')
                ->nullable();

            $table->unsignedTinyInteger('maximum_active_reviews')
                ->default(3);


            /*
            |--------------------------------------------------------------------------
            | COMMUNICATION PREFERENCES
            |--------------------------------------------------------------------------
            */

            $table->enum('preferred_communication_method', [
                'email',
                'mobile',
                'both',
            ])->default('email');

            $table->boolean('receive_review_invitations')
                ->default(true);

            $table->boolean('receive_reminders')
                ->default(true);


            /*
            |--------------------------------------------------------------------------
            | DECLARATIONS
            |--------------------------------------------------------------------------
            */

            $table->boolean('conflict_of_interest_declaration')
                ->default(false);

            $table->timestamp('conflict_of_interest_declared_at')
                ->nullable();

            $table->boolean('reviewer_ethics_declaration')
                ->default(false);

            $table->timestamp('reviewer_ethics_declared_at')
                ->nullable();

            $table->boolean('confidentiality_declaration')
                ->default(false);

            $table->timestamp('confidentiality_declared_at')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | PROFILE UPDATE / AUDIT SUPPORT
            |--------------------------------------------------------------------------
            |
            | Full field-by-field history should eventually be maintained
            | in reviewer_profile_audits.
            |
            */

            $table->timestamp('last_profile_updated_at')
                ->nullable();

            $table->boolean('requires_reverification')
                ->default(false);


            /*
            |--------------------------------------------------------------------------
            | ADMINISTRATIVE NOTES
            |--------------------------------------------------------------------------
            |
            | This should only be visible to authorized editorial staff.
            |
            */

            $table->text('internal_note')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | TIMESTAMPS
            |--------------------------------------------------------------------------
            */

            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | INDEXES FOR REVIEWER SEARCH
            |--------------------------------------------------------------------------
            */

            $table->index([
                'approval_status',
                'available_for_review',
            ], 'reviewer_pool_status_index');

            $table->index([
                'speciality',
                'approval_status',
            ], 'reviewer_speciality_status_index');
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