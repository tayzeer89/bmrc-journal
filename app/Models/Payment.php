<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{


    protected $fillable = [

        'manuscript_id',

        'invoice_no',

        'fee_type',

        'amount',

        'currency',

        'invoice_date',

        'payment_deadline',

        'payment_method',

        'payment_gateway',

        'transaction_id',

        'payment_date',

        'payer_name',

        'payer_mobile',

        'payment_status',

        'verification_status',

        'verified_by',

        'verified_at',

        'remarks',

    ];



    protected $casts = [

        'invoice_date'=>'date',

        'payment_deadline'=>'date',

        'payment_date'=>'datetime',

        'verified_at'=>'datetime',

    ];



    /*
    |--------------------------------------------------------------------------
    | Relationship
    |--------------------------------------------------------------------------
    */


    public function manuscript()
    {
        return $this->belongsTo(
            Manuscript::class
        );
    }


    public function verifier()
    {
        return $this->belongsTo(
            User::class,
            'verified_by'
        );
    }


}