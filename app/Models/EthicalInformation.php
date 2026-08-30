<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class EthicalInformation extends Model
{

    use HasFactory;


    protected $fillable = [

        'manuscript_id',

        'human_participants',

        'animal_study',

        'ethics_committee_name',

        'institution',

        'approval_number',

        'approval_date',

        'informed_consent',

        'consent_type',

        'clinical_trial',

        'trial_registration_no',

        'trial_registry',

    ];



    protected $casts = [

        'approval_date'=>'date',

        'human_participants'=>'boolean',

        'animal_study'=>'boolean',

        'informed_consent'=>'boolean',

        'clinical_trial'=>'boolean',

    ];



    public function manuscript()
    {
        return $this->belongsTo(
            Manuscript::class
        );
    }


}