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
        Schema::create('submission_checklists', function (Blueprint $table) {
            $table->id();

            $table->foreignId('manuscript_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();

            $table->boolean('original_manuscript')->default(false);
            $table->boolean('not_published_elsewhere')->default(false);
            $table->boolean('not_under_consideration_elsewhere')->default(false);
            $table->boolean('authors_approved')->default(false);
            $table->boolean('author_order_approved')->default(false);
            $table->boolean('ethics_information_provided')->default(false);
            $table->boolean('consent_information_provided')->default(false);
            $table->boolean('funding_declared')->default(false);
            $table->boolean('coi_declared')->default(false);
            $table->boolean('journal_guidelines_followed')->default(false);
            $table->boolean('references_checked')->default(false);
            $table->boolean('tables_figures_checked')->default(false);
            $table->boolean('required_files_uploaded')->default(false);
            $table->boolean('corresponding_author_authorized')->default(false);
            $table->boolean('publication_policy_agreed')->default(false);

            $table->foreignId('confirmed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('confirmed_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submission_checklists');
    }
};
