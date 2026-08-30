<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Journal extends Model
{

    use HasFactory;


        protected $fillable = [

            'name',
            'short_name',
            'code',

            'description',

            'issn',
            'eissn',

            'publisher',

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



    /**
     * Manuscripts submitted to this journal
     */
    public function manuscripts(): HasMany
    {

        return $this->hasMany(
            Manuscript::class
        );

    }



    /**
     * Article types allowed for this journal
     */
    public function articleTypes(): BelongsToMany
    {

        return $this->belongsToMany(
            ArticleType::class,
            'journal_article_types'
        )
        ->withPivot([

            'is_active',
            'sort_order'

        ])
        ->withTimestamps();

    }



}