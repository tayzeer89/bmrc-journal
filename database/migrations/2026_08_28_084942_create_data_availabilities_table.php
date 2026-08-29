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
        Schema::create('data_availabilities', function (Blueprint $table) {
            $table->id();

            $table->foreignId('manuscript_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();

            $table->boolean('data_available')->nullable();

            $table->longText('statement')->nullable();

            $table->string('repository')->nullable();
            $table->string('repository_name')->nullable();

            $table->text('doi_url')->nullable();

            $table->string('access_restriction')->nullable();

            $table->text('restriction_reason')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_availabilities');
    }
};
