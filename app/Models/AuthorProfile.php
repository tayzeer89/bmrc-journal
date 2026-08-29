<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AuthorProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'author_id',

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
        'professional_registration_no',
        'research_interest',

        'orcid',
        'researcher_id',
        'scopus_author_id',
        'web_of_science_id',
        'google_scholar_profile',

        'preferred_communication_method',
        'available_for_editorial_communication',

        'profile_completed',
        'status',

        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'date_of_birth' => 'date',

        'available_for_editorial_communication' => 'boolean',

        'profile_completed' => 'boolean',

        'approved_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'approved_by'
        );
    }

    public function qualifications(): HasMany
    {
        return $this->hasMany(
            AuthorQualification::class
        );
    }

    public function affiliations(): BelongsToMany
    {
        return $this->belongsToMany(
            Affiliation::class,
            'author_affiliations'
        );
    }
}