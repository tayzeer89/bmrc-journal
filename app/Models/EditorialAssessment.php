<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EditorialAssessment extends Model
{
    protected $fillable = [

        'manuscript_id',
        'editor_id',
        'assessment_round',

        'scope_status',
        'scientific_quality',
        'methodology_status',
        'novelty_status',
        'reporting_quality',

        'ethical_concern',
        'ethical_comment',

        'conflict_of_interest',
        'conflict_comment',

        'comments',
        'outcome',
        'assessed_at',
    ];


    protected $casts = [

        'ethical_concern' =>
            'boolean',

        'conflict_of_interest' =>
            'boolean',

        'assessed_at' =>
            'datetime',
    ];


    public function manuscript()
    {
        return $this->belongsTo(
            Manuscript::class
        );
    }


    public function editor()
    {
        return $this->belongsTo(
            User::class,
            'editor_id'
        );
    }
}