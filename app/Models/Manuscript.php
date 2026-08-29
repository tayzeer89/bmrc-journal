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
    ];

    protected $casts = [
        'keywords' => 'array',
        'submitted_at' => 'datetime',
        'study_start_date' => 'date',
        'study_end_date' => 'date',
    ];

    public function submitter()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }


    public function authors()
    {
        return $this->belongsToMany(
            User::class,
            'manuscript_authors'
        )->withPivot([
            'author_sequence',
            'author_type',
            'is_corresponding',
            'contribution',
            'coi_declaration',
            'confirmation_status'
        ])->withTimestamps();
    }




    public function files()
    {
        return $this->hasMany(ManuscriptFile::class);
    }

    public function revisions()
    {
        return $this->hasMany(Revision::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}