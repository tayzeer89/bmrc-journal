<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubmissionChecklist extends Model
{
    use HasFactory;


    protected $fillable = [

        'manuscript_id',

        'original_manuscript',
        'not_published_elsewhere',
        'not_under_consideration_elsewhere',
        'authors_approved',
        'author_order_approved',
        'ethics_information_provided',
        'consent_information_provided',
        'funding_declared',
        'coi_declared',
        'journal_guidelines_followed',
        'references_checked',
        'tables_figures_checked',
        'required_files_uploaded',
        'corresponding_author_authorized',
        'publication_policy_agreed',

        'confirmed_by',
        'confirmed_at',

    ];


    protected $casts = [

        'original_manuscript'=>'boolean',
        'not_published_elsewhere'=>'boolean',
        'not_under_consideration_elsewhere'=>'boolean',
        'authors_approved'=>'boolean',
        'author_order_approved'=>'boolean',
        'ethics_information_provided'=>'boolean',
        'consent_information_provided'=>'boolean',
        'funding_declared'=>'boolean',
        'coi_declared'=>'boolean',
        'journal_guidelines_followed'=>'boolean',
        'references_checked'=>'boolean',
        'tables_figures_checked'=>'boolean',
        'required_files_uploaded'=>'boolean',
        'corresponding_author_authorized'=>'boolean',
        'publication_policy_agreed'=>'boolean',

        'confirmed_at'=>'datetime',

    ];



    public function manuscript()
    {
        return $this->belongsTo(
            Manuscript::class
        );
    }

}