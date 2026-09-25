<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('similarity_checks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('manuscript_id')
                ->constrained('manuscripts')
                ->cascadeOnDelete();

            $table->unsignedInteger('check_number')->default(1);

            $table->decimal('similarity_percentage', 5, 2)->nullable();
            $table->decimal('threshold_percentage', 5, 2)->default(20);

            $table->string('software_name')->nullable();
            $table->string('report_file')->nullable();

            $table->foreignId('checked_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('checked_at')->nullable();

            $table->enum('status', [
                'pending',
                'passed',
                'review_required',
                'returned_to_author',
                'escalated',
            ])->default('pending');

            $table->text('comments')->nullable();

            $table->timestamps();

            $table->unique(
                ['manuscript_id', 'check_number'],
                'similarity_manuscript_check_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('similarity_checks');
    }
};