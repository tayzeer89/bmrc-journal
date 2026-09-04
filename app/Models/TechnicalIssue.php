<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TechnicalIssue extends Model
{
    protected $fillable = [

        'technical_check_id',

        'technical_check_item_id',

        'manuscript_file_id',

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
    | Technical Check
    |--------------------------------------------------------------------------
    */

    public function technicalCheck(): BelongsTo
    {
        return $this->belongsTo(
            TechnicalCheck::class,
            'technical_check_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Technical Check Item
    |--------------------------------------------------------------------------
    */

    public function technicalCheckItem(): BelongsTo
    {
        return $this->belongsTo(
            TechnicalCheckItem::class,
            'technical_check_item_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Exact Manuscript File
    |--------------------------------------------------------------------------
    */

    public function manuscriptFile(): BelongsTo
    {
        return $this->belongsTo(
            ManuscriptFile::class,
            'manuscript_file_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Created By
    |--------------------------------------------------------------------------
    */

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Resolved By
    |--------------------------------------------------------------------------
    */

    public function resolvedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'resolved_by'
        );
    }
}
