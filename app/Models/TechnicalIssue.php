<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TechnicalIssue extends Model
{
    protected $fillable = [
        'technical_check_id',
        'technical_check_item_id',
        'category',
        'severity',
        'description',
        'required_action',
        'status',
        'created_by',
        'resolved_by',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function technicalCheck(): BelongsTo
    {
        return $this->belongsTo(
            TechnicalCheck::class,
            'technical_check_id'
        );
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(
            TechnicalCheckItem::class,
            'technical_check_item_id'
        );
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function resolvedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'resolved_by'
        );
    }
}