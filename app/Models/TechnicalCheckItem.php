<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TechnicalCheckItem extends Model
{
    protected $fillable = [

        'technical_check_id',

        'check_key',

        'check_name',

        'sort_order',

        'result',

        'comment',

        'checked_by',

        'checked_at',

    ];


    protected $casts = [

        'checked_at' => 'datetime',

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
    | Checked By
    |--------------------------------------------------------------------------
    */

    public function checkedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'checked_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Issues
    |--------------------------------------------------------------------------
    */

    public function issues(): HasMany
    {
        return $this->hasMany(
            TechnicalIssue::class,
            'technical_check_item_id'
        );
    }
}
