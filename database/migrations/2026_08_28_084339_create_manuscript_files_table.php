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
        Schema::create('manuscript_files', function (Blueprint $table) {
                $table->id();

                $table->foreignId('manuscript_id')
                    ->constrained()
                    ->cascadeOnDelete();

                $table->foreignId('manuscript_version_id')
                    ->nullable()
                    ->constrained()
                    ->nullOnDelete();

                $table->string('file_id')->unique();

                $table->string('file_type');

                $table->string('original_name');

                $table->string('stored_name');

                $table->string('file_path');

                $table->unsignedBigInteger('file_size');

                $table->string('mime_type');

                $table->string('version_number');

                $table->foreignId('uploaded_by')
                    ->constrained('users')
                    ->restrictOnDelete();

                $table->string('status')->default('active');

                $table->foreignId('replacement_file_id')
                    ->nullable()
                    ->constrained('manuscript_files')
                    ->nullOnDelete();

                $table->timestamps();

                $table->softDeletes();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manuscript_files');
    }
};
