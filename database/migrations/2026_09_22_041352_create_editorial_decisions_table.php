<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'editorial_decisions',
            function (Blueprint $table) {

                $table->id();

                $table->foreignId('manuscript_id')
                    ->constrained('manuscripts')
                    ->cascadeOnDelete();

                $table->foreignId('decided_by')
                    ->constrained('users')
                    ->restrictOnDelete();

                $table->foreignId('recommendation_id')
                    ->nullable()
                    ->constrained(
                        'editor_recommendations'
                    )
                    ->nullOnDelete();

                $table->unsignedInteger('decision_round')
                    ->default(1);

                $table->string('decision');

                /*
                 | accept
                 | minor_revision
                 | major_revision
                 | reject
                 */

                $table->longText('decision_letter')
                    ->nullable();

                /*
                 | EIC-only note
                 */

                $table->text('internal_note')
                    ->nullable();

                $table->timestamp('decided_at')
                    ->nullable();

                $table->timestamps();


                $table->index([
                    'manuscript_id',
                    'decision'
                ]);
            }
        );
    }


    public function down(): void
    {
        Schema::dropIfExists(
            'editorial_decisions'
        );
    }
};