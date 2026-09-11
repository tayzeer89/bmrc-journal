<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewerRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'institution',
        'designation',
        'specialization',
        'reason',

        'status',

        'requested_by',

        'processed_by',
        'processed_at',
        'remarks',

        'reviewer_id',
    ];

    protected $casts = [
        'processed_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function requester()
    {
        return $this->belongsTo(
            User::class,
            'requested_by'
        );
    }

    public function processor()
    {
        return $this->belongsTo(
            User::class,
            'processed_by'
        );
    }

    public function reviewer()
    {
        return $this->belongsTo(
            Reviewer::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }
}