<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class DataAvailability extends Model
{

    use HasFactory;


    protected $fillable = [

        'manuscript_id',

        'data_available',

        'statement',

        'repository',

        'repository_name',

        'doi_url',

        'access_restriction',

        'restriction_reason',

    ];



    protected $casts = [

        'data_available' => 'boolean',

    ];



    public function manuscript()
    {
        return $this->belongsTo(
            Manuscript::class
        );
    }


}