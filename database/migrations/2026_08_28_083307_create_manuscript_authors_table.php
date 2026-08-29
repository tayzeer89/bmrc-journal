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
        
        Schema::create('manuscript_authors', function (Blueprint $table) {
                $table->id();

                $table->foreignId('manuscript_id')
                    ->constrained()
                    ->cascadeOnDelete();

                $table->foreignId('author_profile_id')
                    ->nullable()
                    ->constrained()
                    ->nullOnDelete();

                // Snapshot information
                $table->string('title')->nullable();
                $table->string('first_name');
                $table->string('middle_name')->nullable();
                $table->string('last_name');
                $table->string('full_name');

                $table->string('email');
                $table->string('mobile')->nullable();

                $table->string('institution');
                $table->string('department')->nullable();
                $table->string('designation')->nullable();
                $table->string('country');

                $table->string('orcid')->nullable();

                // Article-specific information
                $table->unsignedInteger('author_order');

                $table->boolean('is_corresponding')->default(false);

                $table->string('confirmation_status')
                    ->default('pending');

                $table->text('author_comments')->nullable();

                $table->timestamps();

                $table->unique([
                    'manuscript_id',
                    'author_order'
                ]);
            });



    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manuscript_authors');
    }
};
