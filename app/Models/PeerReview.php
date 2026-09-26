<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PeerReview extends Model
{
    protected $fillable = [
        'manuscript_id',
        'reviewer_id',
        'reviewer_invitation_id',
        'review_round',
        'status',

        'overall_evaluation',

        'comments_to_author',
        'confidential_comments_to_editor',

        'conflict_of_interest',
        'conflict_details',
        'confidentiality_confirmed',
        'reviewer_declaration',

        'started_at',
        'submitted_at',
    ];


    protected $casts = [
        'conflict_of_interest' => 'boolean',
        'confidentiality_confirmed' => 'boolean',
        'reviewer_declaration' => 'boolean',

        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
    ];


    public function manuscript(): BelongsTo
    {
        return $this->belongsTo(
            Manuscript::class
        );
    }


    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(
            Reviewer::class
        );
    }


    public function invitation(): BelongsTo
    {
        return $this->belongsTo(
            ReviewerInvitation::class,
            'reviewer_invitation_id'
        );
    }


    public function assessments(): HasMany
    {
        return $this->hasMany(
            PeerReviewAssessment::class
        );
    }


    public function suggestions(): HasMany
    {
        return $this->hasMany(
            PeerReviewSuggestion::class
        );
    }
}