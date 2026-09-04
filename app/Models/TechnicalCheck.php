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

        'completed_by',

        'started_at',

        'completed_at',

        'comments',

    ];


    protected $casts = [

        'started_at' => 'datetime',

        'completed_at' => 'datetime',

    ];


    /*
    |--------------------------------------------------------------------------
    | Manuscript
    |--------------------------------------------------------------------------
    */

    public function manuscript(): BelongsTo
    {
        return $this->belongsTo(
            Manuscript::class,
            'manuscript_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Assigned User
    |--------------------------------------------------------------------------
    */

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'assigned_to'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Started By
    |--------------------------------------------------------------------------
    */

    public function startedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'started_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Completed By
    |--------------------------------------------------------------------------
    */

    public function completedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'completed_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Checklist Items
    |--------------------------------------------------------------------------
    */

    public function items(): HasMany
    {
        return $this->hasMany(
            TechnicalCheckItem::class,
            'technical_check_id'
        )->orderBy('sort_order');
    }


    /*
    |--------------------------------------------------------------------------
    | Technical Issues
    |--------------------------------------------------------------------------
    */

    public function issues(): HasMany
    {
        return $this->hasMany(
            TechnicalIssue::class,
            'technical_check_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Author correction responses.
    |--------------------------------------------------------------------------
    */

    public function correctionResponses(): HasMany
    {
        return $this->hasMany(
            TechnicalCorrectionResponse::class
        );
    }


}
