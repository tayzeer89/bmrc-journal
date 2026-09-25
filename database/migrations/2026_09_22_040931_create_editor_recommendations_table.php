<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'editor_recommendations',
            function (Blueprint $table) {

                $table->id();

                $table->foreignId('manuscript_id')
                    ->constrained('manuscripts')
                    ->cascadeOnDelete();

                $table->foreignId('editor_id')
                    ->constrained('users')
                    ->restrictOnDelete();

                $table->unsignedInteger('review_round')
                    ->default(1);

                $table->string('recommendation');

                /*
                 | accept
                 | minor_revision
                 | major_revision
                 | reject
                 */

                $table->text('comments');

                $table->boolean('re_review_required')
                    ->default(false);

                $table->timestamp('recommended_at')
                    ->nullable();

                $table->timestamps();


                $table->index([
                    'manuscript_id',
                    'review_round'
                ]);
            }
        );
    }


    public function down(): void
    {
        Schema::dropIfExists(
            'editor_recommendations'
        );
    }
};