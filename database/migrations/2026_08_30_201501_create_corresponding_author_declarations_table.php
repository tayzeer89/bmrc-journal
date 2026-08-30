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
        Schema::create('corresponding_author_declarations', function (Blueprint $table) {

            $table->id();


            $table->foreignId('manuscript_id')
                ->constrained()
                ->cascadeOnDelete();


            $table->foreignId('manuscript_author_id')
                ->constrained()
                ->cascadeOnDelete();



            $table->string('preferred_communication_method');


            $table->boolean(
                'available_for_editorial_communication'
            )->default(true);



            $table->boolean(
                'declaration_confirmed'
            )->default(false);



            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('corresponding_author_declarations');
    }
};
