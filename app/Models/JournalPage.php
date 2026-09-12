<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JournalPage extends Model
{
    protected $fillable = [

        'title',

        'slug',

        'menu_group',

        'content',

        'content_mode',

        'short_description',

        'featured_image',

        'show_in_menu',

        'show_on_homepage',

        'sort_order',

        'status',

        'created_by',

        'updated_by',

    ];


    protected $casts = [

        'show_in_menu' => 'boolean',

        'show_on_homepage' => 'boolean',

        'sort_order' => 'integer',

    ];


    /*
    |--------------------------------------------------------------------------
    | Creator
    |--------------------------------------------------------------------------
    */

    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Updater
    |--------------------------------------------------------------------------
    */

    public function updater(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Published Scope
    |--------------------------------------------------------------------------
    */

    public function scopePublished(
        Builder $query
    ): Builder {

        return $query->where(
            'status',
            'published'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Menu Visible
    |--------------------------------------------------------------------------
    */

    public function scopeMenuVisible(
        Builder $query
    ): Builder {

        return $query->where(
            'show_in_menu',
            true
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Homepage Visible
    |--------------------------------------------------------------------------
    */

    public function scopeHomepageVisible(
        Builder $query
    ): Builder {

        return $query->where(
            'show_on_homepage',
            true
        );
    }
}