<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviewer_lookup_options', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Lookup Type
            |--------------------------------------------------------------------------
            |
            | division_state
            | city_district
            | institution
            | department
            | designation
            | highest_degree
            | specialization
            | research_interest
            | review_keyword
            |
            */

            $table->string('type', 50)->index();

            $table->string('value');

            /*
            | Used mainly for City / District.
            | Example:
            |
            | type         = city_district
            | value        = Dhaka
            | parent_value = Dhaka
            |
            */

            $table->string('parent_value')
                ->nullable()
                ->index();

            $table->boolean('is_active')
                ->default(true)
                ->index();

            $table->unsignedInteger('sort_order')
                ->default(0);

            $table->timestamps();

            $table->unique([
                'type',
                'value',
                'parent_value',
            ], 'reviewer_lookup_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviewer_lookup_options');
    }
};