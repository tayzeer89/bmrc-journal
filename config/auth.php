<?php

use App\Models\User;
use App\Models\Reviewer;

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | The default authentication guard is "web".
    |
    | Internal users and Authors currently use the users table through
    | the web guard and users provider.
    |
    | Reviewers use a separate reviewer guard and reviewers provider.
    |
    */

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],


    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    */

    'guards' => [

        /*
        |--------------------------------------------------------------------------
        | Main Web Guard
        |--------------------------------------------------------------------------
        |
        | This guard is currently used by:
        |
        | - Administrator
        | - Editorial Officer
        | - Editor-in-Chief
        | - Handling / Associate Editor
        | - Finance Officer
        | - Production Staff
        | - Author
        |
        | Authors are identified from the users table by their user_type
        | and corresponding AuthorProfile record.
        |
        */

        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],


        /*
        |--------------------------------------------------------------------------
        | Reviewer Guard
        |--------------------------------------------------------------------------
        |
        | Reviewers use a completely separate authentication system.
        |
        */

        'reviewer' => [
            'driver' => 'session',
            'provider' => 'reviewers',
        ],

    ],


    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    */

    'providers' => [

        /*
        |--------------------------------------------------------------------------
        | Main Users Provider
        |--------------------------------------------------------------------------
        |
        | Used by internal users and Authors.
        |
        */

        'users' => [
            'driver' => 'eloquent',
            'model' => User::class,
        ],


        /*
        |--------------------------------------------------------------------------
        | Reviewer Provider
        |--------------------------------------------------------------------------
        */

        'reviewers' => [
            'driver' => 'eloquent',
            'model' => Reviewer::class,
        ],

    ],


    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    */

    'passwords' => [

        /*
        |--------------------------------------------------------------------------
        | Users Password Broker
        |--------------------------------------------------------------------------
        |
        | Used by:
        |
        | - Internal system users
        | - Authors
        |
        | Because Authors are currently stored in the users table, Author
        | password reset controllers must use:
        |
        | Password::broker('users')
        |
        */

        'users' => [
            'provider' => 'users',

            'table' => env(
                'AUTH_PASSWORD_RESET_TOKEN_TABLE',
                'password_reset_tokens'
            ),

            'expire' => 60,
            'throttle' => 60,
        ],


        /*
        |--------------------------------------------------------------------------
        | Reviewer Password Broker
        |--------------------------------------------------------------------------
        |
        | Reviewer password reset controllers must use:
        |
        | Password::broker('reviewers')
        |
        */

        'reviewers' => [
            'provider' => 'reviewers',

            'table' => env(
                'AUTH_PASSWORD_RESET_TOKEN_TABLE',
                'password_reset_tokens'
            ),

            'expire' => 60,
            'throttle' => 60,
        ],

    ],


    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    */

    'password_timeout' => env(
        'AUTH_PASSWORD_TIMEOUT',
        10800
    ),

];