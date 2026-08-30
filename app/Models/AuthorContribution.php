<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AuthorContribution extends Model
{
    use HasFactory;


    protected $fillable = [

        'manuscript_author_id',

        'conceptualization',
        'methodology',
        'software',
        'validation',
        'formal_analysis',
        'investigation',
        'resources',
        'data_curation',
        'writing_original_draft',
        'writing_review_editing',
        'visualization',
        'supervision',
        'project_administration',
        'funding_acquisition',

    ];


    public function author()
    {
        return $this->belongsTo(
            ManuscriptAuthor::class,
            'manuscript_author_id'
        );
    }

}