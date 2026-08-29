<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    protected $fillable = [
        'name',
        'guard_name',
        'display_name',
        'user_type',
        'description',
        'is_active',
        'is_system_role',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_system_role' => 'boolean',
    ];
}