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
        Schema::create('reviewer_responses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('revision_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('reviewer_label');

            $table->unsignedInteger('comment_number');

            $table->longText('reviewer_comment');

            $table->longText('author_response');

            $table->boolean('change_made')->nullable();

            $table->string('page_number')->nullable();

            $table->string('line_number')->nullable();

            $table->text('revised_section')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviewer_responses');
    }
};
