<?php

namespace App\Models;

use App\Notifications\ReviewerResetPasswordNotification;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;


class Reviewer extends Authenticatable implements CanResetPasswordContract
{
    use HasFactory;
    use Notifiable;
    use CanResetPassword;


    /*
    |--------------------------------------------------------------------------
    | Mass Assignable Attributes
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'name',
        'email',
        'password',

        'email_verified_at',

        'must_change_password',
        'temporary_password_sent_at',
        'password_changed_at',

        'status',
        'created_source',
        'created_by',

        'activated_at',
        'last_login_at',
    ];


    /*
    |--------------------------------------------------------------------------
    | Hidden Attributes
    |--------------------------------------------------------------------------
    */

    protected $hidden = [
        'password',
        'remember_token',
    ];


    /*
    |--------------------------------------------------------------------------
    | Attribute Casting
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'password' => 'hashed',

        'email_verified_at' => 'datetime',

        'must_change_password' => 'boolean',
        'temporary_password_sent_at' => 'datetime',
        'password_changed_at' => 'datetime',

        'activated_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];



    /*
    |--------------------------------------------------------------------------
    | Reviewer Profile
    |--------------------------------------------------------------------------
    */
      public function profile(): HasOne
    {
        return $this->hasOne(
            ReviewerProfile::class,
            'reviewer_id'
        );
    }







    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Account Status Helpers
    |--------------------------------------------------------------------------
    */

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }


    public function isPending(): bool
    {
        return $this->status === 'pending';
    }


    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }


    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }


    /*
    |--------------------------------------------------------------------------
    | Password Helpers
    |--------------------------------------------------------------------------
    */

    public function mustChangePassword(): bool
    {
        return (bool) $this->must_change_password;
    }


    /*
    |--------------------------------------------------------------------------
    | Password Reset Notification
    |--------------------------------------------------------------------------
    |
    | Laravel's default reset notification expects a route named:
    |
    | password.reset
    |
    | But the reviewer authentication system uses:
    |
    | reviewer.password.reset
    |
    | Therefore we send a reviewer-specific password reset notification.
    |
    */

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(
            new ReviewerResetPasswordNotification(
                $token
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Profile Helpers
    |--------------------------------------------------------------------------
    */

    public function hasProfile(): bool
    {
        return $this->profile !== null;
    }


    public function hasCompletedProfile(): bool
    {
        return (bool) $this->profile?->profile_completed;
    }


    public function hasApprovedProfile(): bool
    {
        return $this->profile?->approval_status === 'approved';
    }


    public function profileIsDraft(): bool
    {
        return $this->profile?->approval_status === 'draft';
    }


    public function profilePendingApproval(): bool
    {
        return $this->profile?->approval_status === 'pending_approval';
    }


    public function profileUpdateRequested(): bool
    {
        return $this->profile?->approval_status === 'update_requested';
    }


    public function profileRejected(): bool
    {
        return $this->profile?->approval_status === 'rejected';
    }


    /*
    |--------------------------------------------------------------------------
    | Reviewer Eligibility
    |--------------------------------------------------------------------------
    */

    public function canReceiveReviewInvitations(): bool
    {
        return $this->isApproved()
            && $this->hasApprovedProfile()
            && $this->hasCompletedProfile()
            && (bool) $this->profile?->available_for_review
            && (bool) $this->profile?->receive_review_invitations;
    }




    public function scopeEligibleForReview(Builder $query): Builder
    {
        return $query
            ->where('status', 'approved')
            ->whereHas('profile', function (Builder $q) {

                $q->where('profile_completed', true)
                    ->where('approval_status', 'approved')
                    ->where('available_for_review', true)
                    ->where('receive_review_invitations', true)

                    ->where(function (Builder $availability) {

                        $availability
                            ->whereNull('unavailable_from')
                            ->orWhereNull('unavailable_until')
                            ->orWhereDate(
                                'unavailable_from',
                                '>',
                                now()->toDateString()
                            )
                            ->orWhereDate(
                                'unavailable_until',
                                '<',
                                now()->toDateString()
                            );
                    });
            });
    }
}