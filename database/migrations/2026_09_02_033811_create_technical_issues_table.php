<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('technical_issues', function (Blueprint $table) {

            $table->id();


            /*
            |--------------------------------------------------------------------------
            | Technical Check
            |--------------------------------------------------------------------------
            */

            $table->foreignId('technical_check_id')
                ->constrained('technical_checks')
                ->cascadeOnDelete()
                ->index();


            /*
            |--------------------------------------------------------------------------
            | Related Checklist Item
            |--------------------------------------------------------------------------
            */

            $table->foreignId('technical_check_item_id')
                ->nullable()
                ->constrained('technical_check_items')
                ->nullOnDelete();


            /*
            |--------------------------------------------------------------------------
            | EXACT Uploaded Manuscript File
            |--------------------------------------------------------------------------
            |
            | This identifies the exact uploaded file that needs correction.
            |
            */

            $table->foreignId('manuscript_file_id')
                ->nullable()
                ->constrained('manuscript_files')
                ->nullOnDelete()
                ->index();


            /*
            |--------------------------------------------------------------------------
            | Issue Category
            |--------------------------------------------------------------------------
            */

            $table->enum('category', [

                'formatting',

                'file',

                'article_type',

                'title',

                'author_information',

                'abstract',

                'keywords',

                'figures',

                'tables',

                'supplementary_files',

                'references',

                'word_count',

                'ethics',

                'consent',

                'conflict_of_interest',

                'funding',

                'author_contribution',

                'data_availability',

                'trial_registration',

                'blinding',

                'submission_completeness',

                'declarations',

                'other',

            ])->index();


            /*
            |--------------------------------------------------------------------------
            | Severity
            |--------------------------------------------------------------------------
            */

            $table->enum('severity', [
                'minor',
                'major',
                'critical',
            ])
                ->default('minor')
                ->index();


            /*
            |--------------------------------------------------------------------------
            | Problem
            |--------------------------------------------------------------------------
            */

            $table->text('description');


            /*
            |--------------------------------------------------------------------------
            | Required Action
            |--------------------------------------------------------------------------
            */

            $table->text('required_action')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | Issue Status
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [
                'open',
                'resolved',
                'not_applicable',
            ])
                ->default('open')
                ->index();


            /*
            |--------------------------------------------------------------------------
            | Created By
            |--------------------------------------------------------------------------
            */

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Resolved By
            |--------------------------------------------------------------------------
            */

            $table->foreignId('resolved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Resolution Date
            |--------------------------------------------------------------------------
            */

            $table->timestamp('resolved_at')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | Timestamps
            |--------------------------------------------------------------------------
            */

            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('technical_issues');
    }
};
