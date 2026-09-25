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


    /*
    |--------------------------------------------------------------------------
    | Mass Assignable Fields
    |--------------------------------------------------------------------------
    */

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
        | Submission / Workflow Status
        |--------------------------------------------------------------------------
        */

        'status',

        'current_stage',

        'submission_version',

        'submitted_at',


        /*
        |--------------------------------------------------------------------------
        | Editorial Workflow
        |--------------------------------------------------------------------------
        */

        'handling_editor_id',

        'review_round',

        'revision_round',

        'accepted_at',

        'rejected_at',

        'published_at',


        /*
        |--------------------------------------------------------------------------
        | Draft Management
        |--------------------------------------------------------------------------
        */

        'completion_percentage',

        'last_step',

        'draft_saved_at',
    ];


    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected $casts = [

        /*
        |--------------------------------------------------------------------------
        | JSON
        |--------------------------------------------------------------------------
        */

        'keywords' => 'array',


        /*
        |--------------------------------------------------------------------------
        | Dates
        |--------------------------------------------------------------------------
        */

        'submitted_at' => 'datetime',

        'draft_saved_at' => 'datetime',

        'accepted_at' => 'datetime',

        'rejected_at' => 'datetime',

        'published_at' => 'datetime',


        /*
        |--------------------------------------------------------------------------
        | Integer Values
        |--------------------------------------------------------------------------
        */

        'completion_percentage' => 'integer',

        'last_step' => 'integer',

        'word_count' => 'integer',

        'number_of_tables' => 'integer',

        'number_of_figures' => 'integer',

        'number_of_references' => 'integer',

        'review_round' => 'integer',

        'revision_round' => 'integer',
    ];


    /*
    |--------------------------------------------------------------------------
    |--------------------------------------------------------------------------
    | SUBMISSION RELATIONSHIPS
    |--------------------------------------------------------------------------
    |--------------------------------------------------------------------------
    */


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
    | Manuscript Versions
    |--------------------------------------------------------------------------
    */

    public function versions(): HasMany
    {
        return $this->hasMany(
            ManuscriptVersion::class,
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
    | Latest Payment
    |--------------------------------------------------------------------------
    */

    public function latestPayment(): HasOne
    {
        return $this->hasOne(
            Payment::class,
            'manuscript_id'
        )->latestOfMany();
    }


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


    /*
    |--------------------------------------------------------------------------
    | Latest Technical Check
    |--------------------------------------------------------------------------
    */

    public function latestTechnicalCheck(): HasOne
    {
        return $this->hasOne(
            TechnicalCheck::class,
            'manuscript_id'
        )->latestOfMany('check_number');
    }

   /*
    |--------------------------------------------------------------------------
    | Similarity Check
    |--------------------------------------------------------------------------
    */

    public function similarityChecks()
    {
        return $this->hasMany(SimilarityCheck::class);
    }

    public function latestSimilarityCheck()
    {
        return $this->hasOne(SimilarityCheck::class)->latestOfMany();
    }

    /*
    |--------------------------------------------------------------------------
    |--------------------------------------------------------------------------
    | EDITORIAL WORKFLOW RELATIONSHIPS
    |--------------------------------------------------------------------------
    |--------------------------------------------------------------------------
    */


    /*
    |--------------------------------------------------------------------------
    | Current Handling Editor
    |--------------------------------------------------------------------------
    |
    | The current Handling Editor is also stored directly in manuscripts
    | for fast dashboard/query access.
    |
    */

    public function handlingEditor(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'handling_editor_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | All Editor Assignments
    |--------------------------------------------------------------------------
    |
    | Complete assignment / reassignment history.
    |
    */

    public function editorAssignments(): HasMany
    {
        return $this->hasMany(
            EditorAssignment::class,
            'manuscript_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Current / Latest Editor Assignment
    |--------------------------------------------------------------------------
    */

    public function currentEditorAssignment(): HasOne
    {
        return $this->hasOne(
            EditorAssignment::class,
            'manuscript_id'
        )->latestOfMany();
    }


    /*
    |--------------------------------------------------------------------------
    | Editorial Assessments
    |--------------------------------------------------------------------------
    */

    public function editorialAssessments(): HasMany
    {
        return $this->hasMany(
            EditorialAssessment::class,
            'manuscript_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Latest Editorial Assessment
    |--------------------------------------------------------------------------
    */

    public function latestEditorialAssessment(): HasOne
    {
        return $this->hasOne(
            EditorialAssessment::class,
            'manuscript_id'
        )->latestOfMany();
    }


    /*
    |--------------------------------------------------------------------------
    | Handling Editor Recommendations
    |--------------------------------------------------------------------------
    */

    public function editorRecommendations(): HasMany
    {
        return $this->hasMany(
            EditorRecommendation::class,
            'manuscript_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Latest Handling Editor Recommendation
    |--------------------------------------------------------------------------
    */

    public function latestRecommendation(): HasOne
    {
        return $this->hasOne(
            EditorRecommendation::class,
            'manuscript_id'
        )->latestOfMany();
    }


    /*
    |--------------------------------------------------------------------------
    | Editorial Decisions
    |--------------------------------------------------------------------------
    |
    | Final decisions made by Editor-in-Chief.
    |
    */

    public function editorialDecisions(): HasMany
    {
        return $this->hasMany(
            EditorialDecision::class,
            'manuscript_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Latest Editorial Decision
    |--------------------------------------------------------------------------
    */

    public function latestEditorialDecision(): HasOne
    {
        return $this->hasOne(
            EditorialDecision::class,
            'manuscript_id'
        )->latestOfMany();
    }


    /*
    |--------------------------------------------------------------------------
    |--------------------------------------------------------------------------
    | HELPER METHODS
    |--------------------------------------------------------------------------
    |--------------------------------------------------------------------------
    */


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
    | Accepted Status
    |--------------------------------------------------------------------------
    */

    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }


    /*
    |--------------------------------------------------------------------------
    | Rejected Status
    |--------------------------------------------------------------------------
    */

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }


    /*
    |--------------------------------------------------------------------------
    | Under Peer Review
    |--------------------------------------------------------------------------
    */

    public function isUnderReview(): bool
    {
        return $this->status === 'under_review';
    }


    /*
    |--------------------------------------------------------------------------
    | Has Handling Editor
    |--------------------------------------------------------------------------
    */

    public function hasHandlingEditor(): bool
    {
        return !is_null(
            $this->handling_editor_id
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Awaiting Editor Assignment
    |--------------------------------------------------------------------------
    */

    public function isAwaitingEditorAssignment(): bool
    {
        return $this->status === 'editor_assignment';
    }


    /*
    |--------------------------------------------------------------------------
    | Editorial Assessment
    |--------------------------------------------------------------------------
    */

    public function isUnderEditorialAssessment(): bool
    {
        return $this->status === 'editorial_assessment';
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


    public function reviewerInvitations()
    {
        return $this->hasMany(
            ReviewerInvitation::class,
            'manuscript_id'
        );
    }
}