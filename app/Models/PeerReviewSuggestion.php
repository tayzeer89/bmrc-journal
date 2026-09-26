<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PeerReviewSuggestion extends Model
{
    protected $fillable = [
        'peer_review_id',
        'section',
        'suggestion',
    ];


    public function peerReview(): BelongsTo
    {
        return $this->belongsTo(
            PeerReview::class
        );
    }
}