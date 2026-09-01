<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ManuscriptDetail extends Model
{
    use HasFactory;


    protected $table = 'manuscript_details';


    protected $fillable = [

        'manuscript_id',

        // Scientific Information
        'background',
        'objective',
        'methods',
        'results',
        'conclusion',

        // Trial Registration
        'trial_registration_number',
        'trial_registration_organization',

        // Study Design
        'study_design',
        'other_study_design',

        // Study Period
        'study_start_date',
        'study_end_date',

        // Study Information
        'study_location',
        'sample_size',

        // Funding
        'funding_source',
        'other_funding_source',

        // Ethical Approval
        'ethical_approval_available',
        'ethical_approval_number',
        'ethical_approval_date',
    ];


    protected $casts = [

        'study_start_date' => 'date',

        'study_end_date' => 'date',

        'ethical_approval_date' => 'date',

        'ethical_approval_available' => 'boolean',

        'sample_size' => 'integer',
    ];


    public function manuscript(): BelongsTo
    {
        return $this->belongsTo(
            Manuscript::class,
            'manuscript_id'
        );
    }
}