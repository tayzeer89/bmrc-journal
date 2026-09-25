<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'editorial_assessments',
            function (Blueprint $table) {

                $table->id();


                /*
                |--------------------------------------------------------------------------
                | Manuscript
                |--------------------------------------------------------------------------
                */

                $table->foreignId('manuscript_id')
                    ->constrained('manuscripts')
                    ->cascadeOnDelete();


                /*
                |--------------------------------------------------------------------------
                | Handling Editor
                |--------------------------------------------------------------------------
                */

                $table->foreignId('editor_id')
                    ->constrained('users')
                    ->restrictOnDelete();


                /*
                |--------------------------------------------------------------------------
                | Assessment Round
                |--------------------------------------------------------------------------
                */

                $table->unsignedInteger('assessment_round')
                    ->default(1);


                /*
                |--------------------------------------------------------------------------
                | Scope
                |--------------------------------------------------------------------------
                */

                $table->string('scope_status')
                    ->nullable();


                /*
                |--------------------------------------------------------------------------
                | Scientific Quality
                |--------------------------------------------------------------------------
                */

                $table->string('scientific_quality')
                    ->nullable();


                /*
                |--------------------------------------------------------------------------
                | Methodology
                |--------------------------------------------------------------------------
                */

                $table->string('methodology_status')
                    ->nullable();


                /*
                |--------------------------------------------------------------------------
                | Novelty
                |--------------------------------------------------------------------------
                */

                $table->string('novelty_status')
                    ->nullable();


                /*
                |--------------------------------------------------------------------------
                | Reporting Quality
                |--------------------------------------------------------------------------
                */

                $table->string('reporting_quality')
                    ->nullable();


                /*
                |--------------------------------------------------------------------------
                | Ethical Concern
                |--------------------------------------------------------------------------
                */

                $table->boolean('ethical_concern')
                    ->default(false);

                $table->text('ethical_comment')
                    ->nullable();


                /*
                |--------------------------------------------------------------------------
                | Conflict of Interest
                |--------------------------------------------------------------------------
                */

                $table->boolean('conflict_of_interest')
                    ->default(false);

                $table->text('conflict_comment')
                    ->nullable();


                /*
                |--------------------------------------------------------------------------
                | General Comments
                |--------------------------------------------------------------------------
                */

                $table->text('comments')
                    ->nullable();


                /*
                |--------------------------------------------------------------------------
                | Outcome
                |--------------------------------------------------------------------------
                */

                $table->string('outcome');

                /*
                 | send_for_review
                 | recommend_rejection
                 | return_for_clarification
                 */


                $table->timestamp('assessed_at')
                    ->nullable();


                $table->timestamps();


                $table->index([
                    'manuscript_id',
                    'editor_id'
                ]);
            }
        );
    }


    public function down(): void
    {
        Schema::dropIfExists(
            'editorial_assessments'
        );
    }
};