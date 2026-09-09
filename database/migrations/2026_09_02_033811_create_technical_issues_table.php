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

            // $table->foreignId('technical_check_id')
            //     ->constrained('technical_checks')
            //     ->cascadeOnDelete()
            //     ->index();


            $table->unsignedBigInteger('technical_check_id');

            $table->foreign(
                'technical_check_id',
                'technical_issues_technical_check_id_fk'
            )
                ->references('id')
                ->on('technical_checks')
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Related Checklist Item
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger('technical_check_item_id')
                ->nullable();

            $table->foreign(
                'technical_check_item_id',
                'technical_issues_technical_check_item_id_fk'
            )
                ->references('id')
                ->on('technical_check_items')
                ->nullOnDelete();

            $table->index('technical_check_item_id');


            /*
            |--------------------------------------------------------------------------
            | EXACT Uploaded Manuscript File
            |--------------------------------------------------------------------------
            |
            | This identifies the exact uploaded file that needs correction.
            |
            */

            $table->unsignedBigInteger('manuscript_file_id')
                ->nullable();

            $table->foreign(
                'manuscript_file_id',
                'technical_issues_manuscript_file_id_fk'
            )
                ->references('id')
                ->on('manuscript_files')
                ->nullOnDelete();

            $table->index('manuscript_file_id');


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

            $table->unsignedBigInteger('created_by')
                ->nullable();

            $table->foreign(
                'created_by',
                'technical_issues_created_by_fk'
            )
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->index('created_by');


            /*
            |--------------------------------------------------------------------------
            | Resolved By
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger('resolved_by')
                ->nullable();

            $table->foreign(
                'resolved_by',
                'technical_issues_resolved_by_fk'
            )
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->index('resolved_by');


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
