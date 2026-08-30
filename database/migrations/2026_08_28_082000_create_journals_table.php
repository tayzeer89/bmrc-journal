<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('journals', function (Blueprint $table) {

            $table->id();

            $table->string('name');

            $table->string('short_name')
                ->nullable();

            $table->string('code')
                ->unique();


            $table->text('description')
                ->nullable();


            $table->string('issn')
                ->nullable();

            $table->string('eissn')
                ->nullable();


            $table->string('publisher')
                ->nullable();


            $table->string('website')
                ->nullable();


            $table->string('email')
                ->nullable();


            $table->string('phone')
                ->nullable();


            $table->text('address')
                ->nullable();


            $table->string('frequency')
                ->nullable();


            $table->string('language')
                ->default('English');


            $table->string('country')
                ->default('Bangladesh');


            $table->boolean('is_active')
                ->default(true);


            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journals');
    }
};