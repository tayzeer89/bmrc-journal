<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /*
    |--------------------------------------------------------------------------
    | Mass Assignable Fields
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
        'designation',
        'department',
    ];

    /*
    |--------------------------------------------------------------------------
    | Hidden Fields
    |--------------------------------------------------------------------------
    */

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /*
    |--------------------------------------------------------------------------
    | Attribute Casting
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Author Profile
    |--------------------------------------------------------------------------
    */

    public function authorProfile(): HasOne
    {
        return $this->hasOne(
            AuthorProfile::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Reviewer Profile
    |--------------------------------------------------------------------------
    */

    public function reviewerProfile(): HasOne
    {
        return $this->hasOne(
            ReviewerProfile::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Manuscripts
    |--------------------------------------------------------------------------
    */

    public function manuscripts(): BelongsToMany
    {
        return $this->belongsToMany(
            Manuscript::class,
            'manuscript_authors'
        )
        ->withPivot([
            'author_sequence',
            'author_type',
            'is_corresponding',
            'contribution',
            'coi_declaration',
            'confirmation_status',
        ])
        ->withTimestamps();
    }
}