<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('author_profiles', function (Blueprint $table) {
            $table->id();

            // User account
            $table->foreignId('user_id')
                ->unique()
                ->constrained('users')
                ->cascadeOnDelete();

            // BMRC Author ID
            $table->string('author_id')
                ->unique();


            // Personal Information
            $table->string('title')->nullable();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('display_name');

            $table->string('alternative_email')->nullable();
            $table->string('mobile');
            $table->string('gender')->nullable();
            $table->date('date_of_birth')->nullable();

            $table->string('nationality')->nullable();
            $table->string('country');
            $table->string('division_state')->nullable();
            $table->string('city_district')->nullable();

            $table->text('postal_address')->nullable();
            $table->text('office_address')->nullable();

            // Professional Information
            $table->string('institution')->nullable();
            $table->string('department')->nullable();
            $table->string('designation')->nullable();
            $table->text('academic_degree')->nullable();
            $table->text('specialization')->nullable();
            $table->string('professional_registration_no')->nullable();
            $table->text('research_interest')->nullable();

            // Research IDs
            $table->string('orcid')->nullable()->index();
            $table->string('researcher_id')->nullable();
            $table->string('scopus_author_id')->nullable();
            $table->string('web_of_science_id')->nullable();
            $table->text('google_scholar_profile')->nullable();

            // Communication
            $table->string('preferred_communication_method')->nullable();
            $table->boolean('available_for_editorial_communication')->default(true);

            // Profile status
            $table->boolean('profile_completed')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('author_profiles');
    }
};
