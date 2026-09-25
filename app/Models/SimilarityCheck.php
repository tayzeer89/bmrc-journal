<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SimilarityCheck extends Model
{
    use HasFactory;

    protected $fillable = [
        'manuscript_id',
        'check_number',
        'similarity_percentage',
        'threshold_percentage',
        'software_name',
        'report_file',
        'checked_by',
        'checked_at',
        'status',
        'comments',
    ];

    protected $casts = [
        'similarity_percentage' => 'decimal:2',
        'threshold_percentage' => 'decimal:2',
        'checked_at' => 'datetime',
    ];

    public function manuscript()
    {
        return $this->belongsTo(Manuscript::class);
    }

    public function checkedBy()
    {
        return $this->belongsTo(User::class, 'checked_by');
    }
}