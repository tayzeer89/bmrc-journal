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
        Schema::create('author_affiliations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('manuscript_author_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('affiliation_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->unsignedInteger('affiliation_order')->default(1);

            $table->timestamps();

            $table->unique([
                'manuscript_author_id',
                'affiliation_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('author_affiliations');
    }
};
