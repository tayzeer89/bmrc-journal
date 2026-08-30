<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class FundingInformation extends Model
{

    use HasFactory;


    protected $fillable = [

        'manuscript_id',

        'funding_received',

        'funding_type',

        'funding_organization',

        'grant_number',

        'grant_amount',

        'funding_start_date',

        'funding_end_date',

        'funding_statement',

    ];



    protected $casts=[

        'funding_received'=>'boolean',

        'grant_amount'=>'decimal:2',

        'funding_start_date'=>'date',

        'funding_end_date'=>'date',

    ];



    public function manuscript()
    {
        return $this->belongsTo(
            Manuscript::class
        );
    }

}