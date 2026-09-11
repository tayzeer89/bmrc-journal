<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviewer_profiles', function (Blueprint $table) {

            if (Schema::hasColumn('reviewer_profiles', 'title')) {
                $table->string('title')->nullable()->change();
            }

            if (Schema::hasColumn('reviewer_profiles', 'first_name')) {
                $table->string('first_name')->nullable()->change();
            }

            if (Schema::hasColumn('reviewer_profiles', 'middle_name')) {
                $table->string('middle_name')->nullable()->change();
            }

            if (Schema::hasColumn('reviewer_profiles', 'last_name')) {
                $table->string('last_name')->nullable()->change();
            }

            if (Schema::hasColumn('reviewer_profiles', 'display_name')) {
                $table->string('display_name')->nullable()->change();
            }

            if (Schema::hasColumn('reviewer_profiles', 'mobile')) {
                $table->string('mobile')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        //
    }
};