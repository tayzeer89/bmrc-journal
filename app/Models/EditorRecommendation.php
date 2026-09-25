<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EditorRecommendation extends Model
{
    protected $fillable = [

        'manuscript_id',
        'editor_id',
        'review_round',
        'recommendation',
        'comments',
        're_review_required',
        'recommended_at',
    ];


    protected $casts = [

        're_review_required' =>
            'boolean',

        'recommended_at' =>
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


    public function decisions()
    {
        return $this->hasMany(
            EditorialDecision::class,
            'recommendation_id'
        );
    }
}