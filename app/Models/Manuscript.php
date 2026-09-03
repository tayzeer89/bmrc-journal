<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;



class Manuscript extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [

        /*
        |--------------------------------------------------------------------------
        | Identification
        |--------------------------------------------------------------------------
        */

        'manuscript_id',


        /*
        |--------------------------------------------------------------------------
        | Ownership
        |--------------------------------------------------------------------------
        */

        'submitted_by',


        /*
        |--------------------------------------------------------------------------
        | Journal & Article Type
        |--------------------------------------------------------------------------
        */

        'journal_id',
        'article_type_id',


        /*
        |--------------------------------------------------------------------------
        | Article Information
        |--------------------------------------------------------------------------
        */

        'title',
        'short_title',
        'abstract',
        'keywords',
        'subject_category',
        'subcategory',
        'language',


        /*
        |--------------------------------------------------------------------------
        | Manuscript Statistics
        |--------------------------------------------------------------------------
        */

        'word_count',
        'number_of_tables',
        'number_of_figures',
        'number_of_references',


        /*
        |--------------------------------------------------------------------------
        | Submission Status
        |--------------------------------------------------------------------------
        */

        'status',
        'submission_version',
        'submitted_at',


        /*
        |--------------------------------------------------------------------------
        | Draft Management
        |--------------------------------------------------------------------------
        */

        'completion_percentage',
        'last_step',
        'draft_saved_at',
    ];


    protected $casts = [

        'keywords' => 'array',

        'submitted_at' => 'datetime',

        'draft_saved_at' => 'datetime',

        'completion_percentage' => 'integer',

        'last_step' => 'integer',

        'word_count' => 'integer',

        'number_of_tables' => 'integer',

        'number_of_figures' => 'integer',

        'number_of_references' => 'integer',

    ];


    /*
    |--------------------------------------------------------------------------
    | Submitter
    |--------------------------------------------------------------------------
    */

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'submitted_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Journal
    |--------------------------------------------------------------------------
    */

    public function journal(): BelongsTo
    {
        return $this->belongsTo(
            Journal::class,
            'journal_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Article Type
    |--------------------------------------------------------------------------
    */

    public function articleType(): BelongsTo
    {
        return $this->belongsTo(
            ArticleType::class,
            'article_type_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Step 2 - Manuscript Details
    |--------------------------------------------------------------------------
    */

    public function details(): HasOne
    {
        return $this->hasOne(
            ManuscriptDetail::class,
            'manuscript_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Authors
    |--------------------------------------------------------------------------
    */

    public function authors(): HasMany
    {
        return $this->hasMany(
            ManuscriptAuthor::class,
            'manuscript_id'
        )->orderBy('author_order');
    }


    /*
    |--------------------------------------------------------------------------
    | Files
    |--------------------------------------------------------------------------
    */

    public function files(): HasMany
    {
        return $this->hasMany(
            ManuscriptFile::class,
            'manuscript_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Ethical Information
    |--------------------------------------------------------------------------
    */

    public function ethicalInformation(): HasOne
    {
        return $this->hasOne(
            EthicalInformation::class,
            'manuscript_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Funding Information
    |--------------------------------------------------------------------------
    */

    public function fundingInformation(): HasOne
    {
        return $this->hasOne(
            FundingInformation::class,
            'manuscript_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Conflict of Interest
    |--------------------------------------------------------------------------
    */

    public function conflictOfInterest(): HasOne
    {
        return $this->hasOne(
            ConflictOfInterest::class,
            'manuscript_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Data Availability
    |--------------------------------------------------------------------------
    */

    public function dataAvailability(): HasOne
    {
        return $this->hasOne(
            DataAvailability::class,
            'manuscript_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Acknowledgement
    |--------------------------------------------------------------------------
    */

    public function acknowledgement(): HasOne
    {
        return $this->hasOne(
            Acknowledgement::class,
            'manuscript_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Submission Checklist
    |--------------------------------------------------------------------------
    */

    public function checklist(): HasOne
    {
        return $this->hasOne(
            SubmissionChecklist::class,
            'manuscript_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Revisions
    |--------------------------------------------------------------------------
    */

    public function revisions(): HasMany
    {
        return $this->hasMany(
            Revision::class,
            'manuscript_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Payments
    |--------------------------------------------------------------------------
    */

    public function payments(): HasMany
    {
        return $this->hasMany(
            Payment::class,
            'manuscript_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Technical Checks
    |--------------------------------------------------------------------------
    */

    /*
|--------------------------------------------------------------------------
| Technical Checks
|--------------------------------------------------------------------------
*/

    public function technicalChecks(): HasMany
    {
        return $this->hasMany(
            TechnicalCheck::class,
            'manuscript_id'
        );
    }

    public function latestTechnicalCheck(): HasOne
    {
        return $this->hasOne(
            TechnicalCheck::class,
            'manuscript_id'
        )->latestOfMany('check_number');
    }
        
    /*
    |--------------------------------------------------------------------------
    | Draft Status
    |--------------------------------------------------------------------------
    */

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }


    /*
    |--------------------------------------------------------------------------
    | Submitted Status
    |--------------------------------------------------------------------------
    */

    public function isSubmitted(): bool
    {
        return $this->status === 'submitted';
    }


    /*
    |--------------------------------------------------------------------------
    | Progress
    |--------------------------------------------------------------------------
    */

    public function progress(): int
    {
        return $this->completion_percentage ?? 0;
    }
}