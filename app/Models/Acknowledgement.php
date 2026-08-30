<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Acknowledgement extends Model
{

    use HasFactory;


    protected $fillable = [

        'manuscript_id',

        'applicable',

        'text',

    ];



    protected $casts = [

        'applicable' => 'boolean',

    ];



    public function manuscript()
    {

        return $this->belongsTo(
            Manuscript::class
        );

    }

}