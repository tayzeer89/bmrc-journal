<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ReviewerLookupOption extends Model
{
    protected $fillable = [
        'type',
        'value',
        'parent_value',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeType(
        Builder $query,
        string $type
    ): Builder {
        return $query->where('type', $type);
    }
}