<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'peer_review_assessments',
            function (Blueprint $table) {

                $table->id();

                $table->foreignId('peer_review_id')
                    ->constrained('peer_reviews')
                    ->cascadeOnDelete();

                $table->string('section', 50);

                $table->string('item_key', 100);

                $table->string('item_label');

                $table->enum('assessment', [
                    'agree',
                    'disagree',
                    'need_modification',
                ])->nullable();

                $table->unsignedInteger('sort_order')
                    ->default(0);

                $table->timestamps();

                $table->unique([
                    'peer_review_id',
                    'item_key',
                ]);
            }
        );
    }


    public function down(): void
    {
        Schema::dropIfExists(
            'peer_review_assessments'
        );
    }
};