<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviewers', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | PRIMARY KEY
            |--------------------------------------------------------------------------
            */

            $table->id();


            /*
            |--------------------------------------------------------------------------
            | AUTHENTICATION INFORMATION
            |--------------------------------------------------------------------------
            */

            $table->string('name');

            $table->string('email')
                ->unique();

            $table->string('password')
                ->nullable();

            $table->timestamp('email_verified_at')
                ->nullable();

            $table->rememberToken();


            /*
            |--------------------------------------------------------------------------
            | FIRST LOGIN / PASSWORD SECURITY
            |--------------------------------------------------------------------------
            |
            | must_change_password
            | True when account is created internally with a temporary password.
            |
            | temporary_password_sent_at
            | Records when login credentials were sent to the reviewer.
            |
            | password_changed_at
            | Records when reviewer replaces the temporary password.
            |
            */

            $table->boolean('must_change_password')
                ->default(false)
                ->index();

            $table->timestamp('temporary_password_sent_at')
                ->nullable();

            $table->timestamp('password_changed_at')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | REVIEWER ACCOUNT STATUS
            |--------------------------------------------------------------------------
            |
            | pending
            | Reviewer account exists but application/profile is not yet approved.
            |
            | approved
            | Reviewer is approved and can perform peer review.
            |
            | rejected
            | Reviewer application has been rejected.
            |
            | suspended
            | Reviewer account is temporarily blocked.
            |
            */

            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'suspended',
            ])
                ->default('pending')
                ->index();


            /*
            |--------------------------------------------------------------------------
            | ACCOUNT CREATION SOURCE
            |--------------------------------------------------------------------------
            |
            | self_registration
            | Reviewer registered personally.
            |
            | editorial_officer
            | Reviewer created by Editorial / Journal Officer.
            |
            | administrator
            | Reviewer created by System Administrator.
            |
            | reviewer_request
            | Reviewer account created from an approved reviewer request.
            |
            */

            $table->string('created_source', 50)
                ->nullable()
                ->index();


            /*
            |--------------------------------------------------------------------------
            | CREATED BY
            |--------------------------------------------------------------------------
            |
            | Null for reviewer self-registration.
            |
            | Contains users.id when created internally.
            |
            */

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();


            /*
            |--------------------------------------------------------------------------
            | ACCOUNT ACTIVATION / LOGIN INFORMATION
            |--------------------------------------------------------------------------
            */

            $table->timestamp('activated_at')
                ->nullable();

            $table->timestamp('last_login_at')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | TIMESTAMPS
            |--------------------------------------------------------------------------
            */

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviewers');
    }
};