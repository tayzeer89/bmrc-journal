<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReviewerInvitation extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Mass Assignable Attributes
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'manuscript_id',
        'reviewer_id',
        'invited_by',
        'invitation_token',
        'status',

        'invited_at',
        'responded_at',
        'expires_at',
        'review_deadline',

        'reminder_count',
        'last_reminder_at',

        'response_note',
    ];


    /*
    |--------------------------------------------------------------------------
    | Attribute Casting
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'invited_at' => 'datetime',
        'responded_at' => 'datetime',
        'expires_at' => 'datetime',
        'review_deadline' => 'datetime',
        'last_reminder_at' => 'datetime',

        'reminder_count' => 'integer',
    ];


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Manuscript related to this reviewer invitation.
     */
    public function manuscript(): BelongsTo
    {
        return $this->belongsTo(
            Manuscript::class,
            'manuscript_id'
        );
    }


    /**
     * Reviewer receiving this invitation.
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(
            Reviewer::class,
            'reviewer_id'
        );
    }


    /**
     * Handling Editor / User who sent the invitation.
     */
    public function inviter(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'invited_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Invitation Status Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Determine whether the invitation is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }


    /**
     * Determine whether the invitation has been accepted.
     */
    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }


    /**
     * Determine whether the invitation has been declined.
     */
    public function isDeclined(): bool
    {
        return $this->status === 'declined';
    }


    /**
     * Determine whether the invitation has been cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }


    /*
    |--------------------------------------------------------------------------
    | Invitation Expiry
    |--------------------------------------------------------------------------
    */

    /**
     * Determine whether the invitation has expired.
     *
     * The invitation is considered expired when:
     *
     * 1. Its database status is already "expired", OR
     * 2. It is still pending but expires_at has passed.
     */
    public function isExpired(): bool
    {
        if ($this->status === 'expired') {
            return true;
        }

        if ($this->status !== 'pending') {
            return false;
        }

        if (!$this->expires_at) {
            return false;
        }

        return now()->greaterThan(
            $this->expires_at
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Can Reviewer Respond?
    |--------------------------------------------------------------------------
    */

    /**
     * Reviewer may respond only while the invitation is:
     *
     * - pending
     * - not expired
     * - not cancelled
     */
    public function canRespond(): bool
    {
        return $this->isPending()
            &&
            !$this->isExpired()
            &&
            !$this->isCancelled();
    }


    /*
    |--------------------------------------------------------------------------
    | Mark Invitation as Expired
    |--------------------------------------------------------------------------
    */

    /**
     * Update the invitation status to expired when necessary.
     */
    public function markExpiredIfNecessary(): bool
    {
        if (
            $this->status === 'pending'
            &&
            $this->expires_at
            &&
            now()->greaterThan(
                $this->expires_at
            )
        ) {
            $this->update([
                'status' => 'expired',
            ]);

            return true;
        }

        return false;
    }


    /*
    |--------------------------------------------------------------------------
    | Accept Invitation
    |--------------------------------------------------------------------------
    */

    /**
     * Mark this invitation as accepted.
     */
    public function accept(): bool
    {
        if (!$this->canRespond()) {
            return false;
        }

        return $this->update([
            'status' => 'accepted',

            'responded_at' => now(),

            'response_note' => null,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Decline Invitation
    |--------------------------------------------------------------------------
    */

    /**
     * Mark this invitation as declined.
     */
    public function decline(
        ?string $responseNote = null
    ): bool {
        if (!$this->canRespond()) {
            return false;
        }

        return $this->update([
            'status' => 'declined',

            'responded_at' => now(),

            'response_note' => $responseNote,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Token Lookup
    |--------------------------------------------------------------------------
    */

    /**
     * Find an invitation using the secure invitation token.
     */
    public static function findByToken(
        string $token
    ): ?self {
        return static::query()
            ->where(
                'invitation_token',
                $token
            )
            ->first();
    }


    /*
    |--------------------------------------------------------------------------
    | Reviewer Ownership Check
    |--------------------------------------------------------------------------
    */

    /**
     * Determine whether this invitation belongs to a reviewer.
     */
    public function belongsToReviewer(
        Reviewer $reviewer
    ): bool {
        return (int) $this->reviewer_id
            ===
            (int) $reviewer->id;
    }


    /*
    |--------------------------------------------------------------------------
    | Review Deadline Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Determine whether the review deadline has passed.
     */
    public function isReviewOverdue(): bool
    {
        if (!$this->review_deadline) {
            return false;
        }

        return now()->greaterThan(
            $this->review_deadline
        );
    }


    /**
     * Number of days remaining for review.
     */
    public function reviewDaysRemaining(): ?int
    {
        if (!$this->review_deadline) {
            return null;
        }

        if ($this->isReviewOverdue()) {
            return 0;
        }

        return now()
            ->startOfDay()
            ->diffInDays(
                $this->review_deadline->copy()->startOfDay()
            );
    }
}