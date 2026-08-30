<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\AuthorContribution;
use App\Models\Affiliation;

class ManuscriptAuthor extends Model
{

    use HasFactory;


    protected $fillable = [

        'manuscript_id',
        'author_profile_id',

        'title',
        'first_name',
        'middle_name',
        'last_name',
        'full_name',

        'email',
        'mobile',

        'institution',
        'department',
        'designation',
        'country',

        'orcid',

        'author_order',

        'is_corresponding',

        'confirmation_status',

        'author_comments',

    ];



    protected $casts = [

        'is_corresponding'=>'boolean',

    ];



    /*
    |--------------------------------------------------------------------------
    | Manuscript Relation
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
    | Author Profile Relation
    |--------------------------------------------------------------------------
    */

    public function profile()
    {
        return $this->belongsTo(
            AuthorProfile::class,
            'author_profile_id'
        );
    }



    /*
    |--------------------------------------------------------------------------
    | CRediT Contribution Relation
    |--------------------------------------------------------------------------
    */

    public function contribution()
    {
        return $this->hasOne(
            AuthorContribution::class,
            'manuscript_author_id'
        );
    }



    /*
    |--------------------------------------------------------------------------
    | Multiple Affiliation Relation
    |--------------------------------------------------------------------------
    */

    public function affiliations()
    {
        return $this->belongsToMany(
            Affiliation::class,
            'author_affiliations',
            'manuscript_author_id',
            'affiliation_id'
        )
        ->withPivot(
            'affiliation_order'
        )
        ->withTimestamps();
    }



}