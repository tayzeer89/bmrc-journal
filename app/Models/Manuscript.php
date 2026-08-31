<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\ManuscriptAuthor;
use App\Models\ManuscriptFile;
use App\Models\Revision;
use App\Models\Payment;
use App\Models\ArticleType;
use App\Models\Journal;
use App\Models\EthicalInformation;
use App\Models\FundingInformation;
use App\Models\ConflictOfInterest;

class Manuscript extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'manuscript_id',
        'submitted_by',
        'journal_id',
        'article_type_id',
        'title',
        'short_title',
        'abstract',
        'keywords',
        'subject_category',
        'subcategory',
        'language',
        'word_count',
        'number_of_tables',
        'number_of_figures',
        'number_of_references',
        'background',
        'objective',
        'methods',
        'results',
        'conclusion',
        'trial_registration_number',
        'trial_registration_organization',
        'study_design',
        'study_start_date',
        'study_end_date',
        'study_location',
        'sample_size',
        'status',
        'submission_version',
        'submitted_at',
        // Draft Management
        'completion_percentage',
        'last_step',
        'draft_saved_at',
    ];

    protected $casts = [
        'keywords' => 'array',
        'submitted_at' => 'datetime',
        'study_start_date' => 'date',
        'study_end_date' => 'date',
        'completion_percentage' => 'integer',
        'draft_saved_at'=>'datetime',
    ];

    public function submitter()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }


    public function authors()
    {
        return $this->hasMany(
            ManuscriptAuthor::class,
            'manuscript_id'
        )
        ->orderBy('author_order');
    }


    public function files()
        {
            return $this->hasMany(
                ManuscriptFile::class,
                'manuscript_id'
            );
        }

        public function ethicalInformation()
            {
                return $this->hasOne(
                    EthicalInformation::class,
                    'manuscript_id'
                );
            }


        public function fundingInformation()
        {
            return $this->hasOne(
                FundingInformation::class,
                'manuscript_id'
            );
        }

        public function conflictOfInterest()
        {
            return $this->hasOne(
                ConflictOfInterest::class,
                'manuscript_id'
            );
        }

        public function dataAvailability()
        {
            return $this->hasOne(
                DataAvailability::class,
                'manuscript_id'
            );
        }

        public function acknowledgement()
            {
                return $this->hasOne(
                    Acknowledgement::class,
                    'manuscript_id'
                );
            }

        
        public function checklist()
            {
                return $this->hasOne(
                    SubmissionChecklist::class
                );
            }
            
        public function revisions()
        {
            return $this->hasMany(Revision::class);
        }

        public function payments()
        {
            return $this->hasMany(
                Payment::class
            );
        }

        public function journal()
        {
            return $this->belongsTo(
                Journal::class
            );
        }

        public function details()
        {
            return $this->hasOne(
                ManuscriptDetail::class,
                'manuscript_id'
            );
        }


        public function articleType()
        {
            return $this->belongsTo(
                ArticleType::class,
                'article_type_id'
            );
        }

        public function isDraft()
        {
            return $this->status === 'draft';
        }


        public function isSubmitted()
        {
            return $this->status === 'submitted';
        }


        public function progress()
        {
            return $this->completion_percentage ?? 0;
        }
                



}