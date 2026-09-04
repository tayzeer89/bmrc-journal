<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ManuscriptVersion extends Model
{
    /**
     * Your migration does not create updated_at.
     */
    public const UPDATED_AT = null;


    protected $fillable = [
        'manuscript_id',
        'version_number',
        'version_type',
        'created_by',
        'change_summary',
    ];


    /**
     * Manuscript.
     */
    public function manuscript(): BelongsTo
    {
        return $this->belongsTo(Manuscript::class);
    }


    /**
     * User who created the version.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }


    /**
     * Files belonging to this version.
     */
    public function files(): HasMany
    {
        return $this->hasMany(
            ManuscriptFile::class
        );
    }
}