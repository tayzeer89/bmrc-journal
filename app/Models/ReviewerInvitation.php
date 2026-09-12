<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class ReviewerInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'manuscript_id',
        'reviewer_id',
        'invited_by',
        'invitation_token',
        'status',
        'invited_at',
        'responded_at',
        'expires_at',
        'reminder_count',
        'last_reminder_at',
        'response_note',
    ];

    protected $casts = [
        'invited_at' => 'datetime',
        'responded_at' => 'datetime',
        'expires_at' => 'datetime',
        'last_reminder_at' => 'datetime',
        'reminder_count' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function manuscript()
    {
        return $this->belongsTo(
            Manuscript::class
        );
    }

    public function reviewer()
    {
        return $this->belongsTo(
            Reviewer::class
        );
    }

    public function inviter()
    {
        return $this->belongsTo(
            User::class,
            'invited_by'
        );
    }
}