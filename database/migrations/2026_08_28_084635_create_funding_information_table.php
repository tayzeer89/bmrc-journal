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
        Schema::create('funding_information', function (Blueprint $table) {
            $table->id();

            $table->foreignId('manuscript_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->boolean('funding_received')->default(false);

            $table->string('funding_type')->nullable();
            $table->string('funding_organization')->nullable();
            $table->string('grant_number')->nullable();

            $table->decimal('grant_amount', 15, 2)->nullable();

            $table->date('funding_start_date')->nullable();
            $table->date('funding_end_date')->nullable();

            $table->text('funding_statement')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('funding_information');
    }
};
