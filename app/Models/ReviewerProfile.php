<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReviewerProfile extends Model
{
    use HasFactory;

    protected $table = 'reviewer_profiles';

    protected $fillable = [

        'reviewer_id',

        'application_id',
        'reviewer_code',

        'title',
        'first_name',
        'middle_name',
        'last_name',
        'display_name',

        'alternative_email',
        'mobile',

        'gender',
        'date_of_birth',
        'nationality',
        'country',
        'division_state',
        'city_district',
        'postal_address',
        'office_address',

        'institution',
        'department',
        'designation',
        'academic_degree',
        'specialization',
        'research_interest',
        'professional_registration_no',

        'orcid',
        'researcher_id',
        'scopus_author_id',
        'web_of_science_id',
        'google_scholar_profile',

        'reviewer_expertise',
        'keywords',

        'cv_file',

        'status',
        'applied_at',
        'approved_at',
        'rejected_at',

        'approved_by',
        'approval_note',
        'rejection_reason',

        'profile_completed',
        'available_for_review',

        'preferred_communication_method',

        'payment_method',
        'payment_account',

        'taxpayer_type',
        'tin_number',
        'nid_number',
        'bin_number',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'applied_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'profile_completed' => 'boolean',
        'available_for_review' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Reviewer Account
    |--------------------------------------------------------------------------
    */

    public function reviewer()
    {
        return $this->belongsTo(Reviewer::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Approved By
    |--------------------------------------------------------------------------
    */

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}