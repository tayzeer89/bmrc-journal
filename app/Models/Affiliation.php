<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Affiliation extends Model
{

    use HasFactory;


    protected $fillable=[

        'institution_name',
        'faculty_institute',
        'department',
        'unit_section',
        'designation',
        'address',
        'city',
        'country',
        'postal_code',
        'institution_email',
        'institution_website',

    ];



    public function authors()
    {
        return $this->belongsToMany(
            ManuscriptAuthor::class,
            'author_affiliations'
        );
    }

}