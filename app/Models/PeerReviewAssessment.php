<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PeerReviewAssessment extends Model
{
    protected $fillable = [
        'peer_review_id',
        'section',
        'item_key',
        'item_label',
        'assessment',
        'sort_order',
    ];


    protected $casts = [
        'sort_order' => 'integer',
    ];


    public function peerReview(): BelongsTo
    {
        return $this->belongsTo(
            PeerReview::class
        );
    }
}