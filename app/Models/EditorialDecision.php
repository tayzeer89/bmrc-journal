<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EditorialDecision extends Model
{
    protected $fillable = [

        'manuscript_id',
        'decided_by',
        'recommendation_id',
        'decision_round',
        'decision',
        'decision_letter',
        'internal_note',
        'decided_at',
    ];


    protected $casts = [

        'decided_at' =>
            'datetime',
    ];


    public function manuscript()
    {
        return $this->belongsTo(
            Manuscript::class
        );
    }


    public function decidedBy()
    {
        return $this->belongsTo(
            User::class,
            'decided_by'
        );
    }


    public function recommendation()
    {
        return $this->belongsTo(
            EditorRecommendation::class,
            'recommendation_id'
        );
    }
}