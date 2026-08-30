<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManuscriptDetail extends Model
{


    protected $fillable = [

        'manuscript_id',

        'background',
        'objective',
        'methods',
        'results',
        'conclusion',

        'trial_registration_number',
        'trial_registration_organization',

        'study_design',

        'study_start_date',
        'study_end_date',

        'study_location',

        'sample_size',

        'funding_source',

        'ethical_approval_available',

        'ethical_approval_number',

        'ethical_approval_date',

    ];



    public function manuscript()
    {

        return $this->belongsTo(
            Manuscript::class
        );

    }

}