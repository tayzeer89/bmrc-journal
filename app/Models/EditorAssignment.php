<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EditorAssignment extends Model
{
    protected $fillable = [
        'manuscript_id',
        'editor_id',
        'assigned_by',
        'assignment_round',
        'status',
        'assignment_note',
        'due_date',
        'decline_reason',
        'assigned_at',
        'accepted_at',
        'declined_at',
        'completed_at',
        'cancelled_at',
    ];


    protected $casts = [
        'due_date'      => 'date',
        'assigned_at'   => 'datetime',
        'accepted_at'   => 'datetime',
        'declined_at'   => 'datetime',
        'completed_at'  => 'datetime',
        'cancelled_at'  => 'datetime',
    ];


    public function manuscript(): BelongsTo
    {
        return $this->belongsTo(
            Manuscript::class,
            'manuscript_id'
        );
    }


    public function editor(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'editor_id'
        );
    }


    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'assigned_by'
        );
    }
}