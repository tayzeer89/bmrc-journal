<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TechnicalCorrectionResponse extends Model
{
    /**
     * Mass assignable fields.
     */
    protected $fillable = [
        'manuscript_id',
        'technical_check_id',
        'manuscript_version_id',
        'submitted_by',
        'response',
        'submitted_at',
    ];


    /**
     * Cast dates.
     */
    protected $casts = [
        'submitted_at' => 'datetime',
    ];


    /**
     * Manuscript.
     */
    public function manuscript(): BelongsTo
    {
        return $this->belongsTo(Manuscript::class);
    }


    /**
     * Technical check being answered.
     */
    public function technicalCheck(): BelongsTo
    {
        return $this->belongsTo(
            TechnicalCheck::class
        );
    }


    /**
     * Version created by the correction submission.
     */
    public function manuscriptVersion(): BelongsTo
    {
        return $this->belongsTo(
            ManuscriptVersion::class
        );
    }


    /**
     * Author who submitted the correction.
     */
    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'submitted_by'
        );
    }
}