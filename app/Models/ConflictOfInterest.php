<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class ConflictOfInterest extends Model
{

    use HasFactory;


    protected $fillable=[

        'manuscript_id',

        'conflict_exists',

        'conflict_description',

        'author_declaration',

        'all_authors_agreed',

        'declared_at',

    ];



    protected $casts=[

        'conflict_exists'=>'boolean',

        'all_authors_agreed'=>'boolean',

        'declared_at'=>'datetime',

    ];



    public function manuscript()
    {
        return $this->belongsTo(
            Manuscript::class
        );
    }

}