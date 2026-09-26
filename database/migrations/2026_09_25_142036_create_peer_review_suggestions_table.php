<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'peer_review_suggestions',
            function (Blueprint $table) {

                $table->id();

                $table->foreignId('peer_review_id')
                    ->constrained('peer_reviews')
                    ->cascadeOnDelete();

                $table->string('section', 50);

                $table->longText('suggestion')
                    ->nullable();

                $table->timestamps();

                $table->unique([
                    'peer_review_id',
                    'section',
                ]);
            }
        );
    }


    public function down(): void
    {
        Schema::dropIfExists(
            'peer_review_suggestions'
        );
    }
};