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
        Schema::create('author_contributions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('manuscript_author_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->boolean('conceptualization')->default(false);
            $table->boolean('methodology')->default(false);
            $table->boolean('software')->default(false);
            $table->boolean('validation')->default(false);
            $table->boolean('formal_analysis')->default(false);
            $table->boolean('investigation')->default(false);
            $table->boolean('resources')->default(false);
            $table->boolean('data_curation')->default(false);
            $table->boolean('writing_original_draft')->default(false);
            $table->boolean('writing_review_editing')->default(false);
            $table->boolean('visualization')->default(false);
            $table->boolean('supervision')->default(false);
            $table->boolean('project_administration')->default(false);
            $table->boolean('funding_acquisition')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('author_contributions');
    }
};
