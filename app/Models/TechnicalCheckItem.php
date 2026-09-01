<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechnicalCheckItem extends Model
{
    use HasFactory;

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

    public function technicalCheck()
    {
        return $this->belongsTo(
            TechnicalCheck::class
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Checked By
    |--------------------------------------------------------------------------
    */

    public function checkedBy()
    {
        return $this->belongsTo(
            User::class,
            'checked_by'
        );
    }

}