<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TechnicalCheck extends Model
{
    protected $fillable = [
        'manuscript_id',
        'check_number',
        'status',
        'overall_result',
        'assigned_to',
        'started_by',
        'started_at',
        'completed_by',
        'completed_at',
        'comments',
    ];


    public function manuscript(): BelongsTo
    {
        return $this->belongsTo(Manuscript::class);
    }


    public function items(): HasMany
    {
        return $this->hasMany(
            TechnicalCheckItem::class
        );
    }


    public function issues(): HasMany
    {
        return $this->hasMany(
            TechnicalIssue::class
        );
    }


    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'assigned_to'
        );
    }


    public function startedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'started_by'
        );
    }


    public function completedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'completed_by'
        );
    }
}