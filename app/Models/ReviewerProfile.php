<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReviewerProfile extends Model
{
    use HasFactory;

    protected $fillable = [

        /*
        |--------------------------------------------------------------------------
        | Reviewer
        |--------------------------------------------------------------------------
        */

        'reviewer_id',

        /*
        |--------------------------------------------------------------------------
        | Identification
        |--------------------------------------------------------------------------
        */

        'application_id',
        'reviewer_code',

        /*
        |--------------------------------------------------------------------------
        | Personal Information
        |--------------------------------------------------------------------------
        */

        'title',
        'first_name',
        'middle_name',
        'last_name',
        'display_name',

        /*
        |--------------------------------------------------------------------------
        | Contact Information
        |--------------------------------------------------------------------------
        */

        'alternative_email',
        'mobile',
        'alternative_mobile',

        /*
        |--------------------------------------------------------------------------
        | Location
        |--------------------------------------------------------------------------
        */

        'gender',
        'date_of_birth',
        'nationality',
        'country',
        'division_state',
        'city_district',
        'postal_code',
        'postal_address',
        'office_address',

        /*
        |--------------------------------------------------------------------------
        | Professional Information
        |--------------------------------------------------------------------------
        */

        'institution',
        'department',
        'designation',
        'organization_type',
        'professional_experience',
        'years_of_experience',
        'professional_registration_no',

        /*
        |--------------------------------------------------------------------------
        | Qualifications
        |--------------------------------------------------------------------------
        */

        'academic_qualifications',
        'professional_qualifications',
        'highest_degree',
        'highest_degree_institution',
        'year_of_highest_degree',

        /*
        |--------------------------------------------------------------------------
        | Speciality / Specialization
        |--------------------------------------------------------------------------
        */

        'speciality',
        'sub_speciality',
        'specialization',

        /*
        |--------------------------------------------------------------------------
        | Research Information
        |--------------------------------------------------------------------------
        */

        'research_interests',
        'publication_count',
        'first_author_publications',
        'corresponding_author_publications',
        'research_experience',

        /*
        |--------------------------------------------------------------------------
        | Research Identifiers
        |--------------------------------------------------------------------------
        */

        'orcid',
        'researcher_id',
        'scopus_author_id',
        'web_of_science_id',
        'google_scholar_profile',

        /*
        |--------------------------------------------------------------------------
        | Expertise
        |--------------------------------------------------------------------------
        */

        'areas_of_expertise',
        'primary_expertise',
        'secondary_expertise',
        'methodological_expertise',
        'expertise_keywords',

        /*
        |--------------------------------------------------------------------------
        | Reviewing Experience
        |--------------------------------------------------------------------------
        */

        'reviewing_experience',
        'previous_journal_experience',
        'external_reviews_completed',

        /*
        |--------------------------------------------------------------------------
        | Memberships
        |--------------------------------------------------------------------------
        */

        'professional_memberships',

        /*
        |--------------------------------------------------------------------------
        | CV
        |--------------------------------------------------------------------------
        */

        'cv_file',

        /*
        |--------------------------------------------------------------------------
        | Profile Completion
        |--------------------------------------------------------------------------
        */

        'profile_completed',
        'profile_completion_percentage',
        'profile_completed_at',

        /*
        |--------------------------------------------------------------------------
        | Approval
        |--------------------------------------------------------------------------
        */

        'approval_status',
        'submitted_for_approval_at',
        'approved_at',
        'rejected_at',
        'update_requested_at',
        'approved_by',
        'approval_note',
        'rejection_reason',
        'profile_update_request',

        /*
        |--------------------------------------------------------------------------
        | Availability
        |--------------------------------------------------------------------------
        */

        'available_for_review',
        'unavailable_from',
        'unavailable_until',
        'maximum_active_reviews',

        /*
        |--------------------------------------------------------------------------
        | Communication
        |--------------------------------------------------------------------------
        */

        'preferred_communication_method',
        'receive_review_invitations',
        'receive_reminders',

        /*
        |--------------------------------------------------------------------------
        | Declarations
        |--------------------------------------------------------------------------
        */

        'conflict_of_interest_declaration',
        'conflict_of_interest_declared_at',

        'reviewer_ethics_declaration',
        'reviewer_ethics_declared_at',

        'confidentiality_declaration',
        'confidentiality_declared_at',

        /*
        |--------------------------------------------------------------------------
        | Audit / Verification
        |--------------------------------------------------------------------------
        */

        'last_profile_updated_at',
        'requires_reverification',

        /*
        |--------------------------------------------------------------------------
        | Administrative
        |--------------------------------------------------------------------------
        */

        'internal_note',
    ];

    protected $casts = [

        'date_of_birth' => 'date',

        'years_of_experience' => 'integer',
        'year_of_highest_degree' => 'integer',

        'publication_count' => 'integer',
        'first_author_publications' => 'integer',
        'corresponding_author_publications' => 'integer',
        'external_reviews_completed' => 'integer',

        'profile_completed' => 'boolean',
        'profile_completion_percentage' => 'integer',
        'profile_completed_at' => 'datetime',

        'submitted_for_approval_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'update_requested_at' => 'datetime',

        'available_for_review' => 'boolean',
        'unavailable_from' => 'date',
        'unavailable_until' => 'date',
        'maximum_active_reviews' => 'integer',

        'receive_review_invitations' => 'boolean',
        'receive_reminders' => 'boolean',

        'conflict_of_interest_declaration' => 'boolean',
        'conflict_of_interest_declared_at' => 'datetime',

        'reviewer_ethics_declaration' => 'boolean',
        'reviewer_ethics_declared_at' => 'datetime',

        'confidentiality_declaration' => 'boolean',
        'confidentiality_declared_at' => 'datetime',

        'last_profile_updated_at' => 'datetime',
        'requires_reverification' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(
            Reviewer::class
        );
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'approved_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Approval Status Helpers
    |--------------------------------------------------------------------------
    */

    public function isDraft(): bool
    {
        return $this->approval_status === 'draft';
    }

    public function isPendingApproval(): bool
    {
        return $this->approval_status === 'pending_approval';
    }

    public function isUpdateRequested(): bool
    {
        return $this->approval_status === 'update_requested';
    }

    public function isApproved(): bool
    {
        return $this->approval_status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->approval_status === 'rejected';
    }

    /*
    |--------------------------------------------------------------------------
    | Profile Helpers
    |--------------------------------------------------------------------------
    */

    public function isCompleted(): bool
    {
        return (bool) $this->profile_completed;
    }

    public function completionPercentage(): int
    {
        return (int) $this->profile_completion_percentage;
    }

    public function hasCv(): bool
    {
        return filled($this->cv_file);
    }

    public function isAvailableForReview(): bool
    {
        if (! $this->available_for_review) {
            return false;
        }

        if (
            $this->unavailable_from &&
            $this->unavailable_until &&
            now()->between(
                $this->unavailable_from,
                $this->unavailable_until
            )
        ) {
            return false;
        }

        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | Multiple-value Helpers
    |--------------------------------------------------------------------------
    |
    | At present these values are stored as comma-separated TEXT.
    |--------------------------------------------------------------------------
    */

    public function specializationList(): array
    {
        return $this->textToArray(
            $this->specialization
        );
    }

    public function researchInterestList(): array
    {
        return $this->textToArray(
            $this->research_interests
        );
    }

    public function expertiseKeywordList(): array
    {
        return $this->textToArray(
            $this->expertise_keywords
        );
    }

    private function textToArray(?string $value): array
    {
        if (blank($value)) {
            return [];
        }

        return collect(
            preg_split('/[,;|]+/', $value)
        )
            ->map(fn ($item) => trim($item))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }
}