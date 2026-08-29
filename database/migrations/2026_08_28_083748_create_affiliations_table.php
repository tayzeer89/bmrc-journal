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
        Schema::create('affiliations', function (Blueprint $table) {
            $table->id();

            $table->string('institution_name');
            $table->string('faculty_institute')->nullable();
            $table->string('department')->nullable();
            $table->string('unit_section')->nullable();
            $table->string('designation')->nullable();

            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country');
            $table->string('postal_code')->nullable();

            $table->string('institution_email')->nullable();
            $table->string('institution_website')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliations');
    }
};
