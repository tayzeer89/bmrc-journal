<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechnicalCheck extends Model
{
    use HasFactory;

    protected $fillable = [

        'manuscript_id',
        'check_number',
        'status',

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

    public function manuscript()
    {
        return $this->belongsTo(
            Manuscript::class
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Assigned Officer
    |--------------------------------------------------------------------------
    */

    public function assignedOfficer()
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

    public function startedBy()
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

    public function completedBy()
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

    public function items()
    {
        return $this->hasMany(
            TechnicalCheckItem::class
        )->orderBy('sort_order');
    }

}