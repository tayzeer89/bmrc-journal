<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Journal extends Model
{
    protected $fillable = [
        'name',
        'short_name',
        'issn',
        'e_issn',
        'publisher',
        'description',
        'website',
        'email',
        'phone',
        'address',
        'frequency',
        'language',
        'country',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function manuscripts(): HasMany
    {
        return $this->hasMany(Manuscript::class);
    }
}