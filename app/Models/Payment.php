<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Table
    |--------------------------------------------------------------------------
    */

    protected $table = 'payments';


    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        // Manuscript
        'manuscript_id',

        // Invoice
        'invoice_no',
        'fee_type',
        'amount',
        'currency',
        'invoice_date',
        'payment_deadline',

        // Payment Request
        'sent_to_author_at',

        // Author Payment
        'payment_method',
        'payment_gateway',
        'transaction_id',
        'payment_date',
        'payer_name',
        'payer_mobile',

        // Status
        'payment_status',
        'verification_status',

        // Verification
        'verified_by',
        'verified_at',

        // Staff
        'created_by',

        // Remarks
        'remarks',
        'verification_notes',
    ];


    /*
    |--------------------------------------------------------------------------
    | Attribute Casting
    |--------------------------------------------------------------------------
    */

    protected $casts = [

        'amount' => 'decimal:2',

        'invoice_date' => 'date',

        'payment_deadline' => 'date',

        'payment_date' => 'datetime',

        'sent_to_author_at' => 'datetime',

        'verified_at' => 'datetime',
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
    | Invoice Created By
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
    | Verified By
    |--------------------------------------------------------------------------
    */

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'verified_by'
        );
    }
}