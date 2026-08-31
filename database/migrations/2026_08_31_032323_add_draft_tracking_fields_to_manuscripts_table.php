<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {

        Schema::table('manuscripts', function (Blueprint $table) {


            /*
            |--------------------------------------------------------------------------
            | Draft Progress Tracking
            |--------------------------------------------------------------------------
            */


            $table->unsignedTinyInteger('completion_percentage')
                ->default(0)
                ->after('status');


            /*
            |--------------------------------------------------------------------------
            | Current Submission Step
            | 1 = Manuscript Info
            | 2 = Authors
            | 3 = Files
            | 4 = Ethical
            | 5 = Funding
            | 6 = Checklist
            | 7 = Review Submit
            |--------------------------------------------------------------------------
            */


            $table->unsignedTinyInteger('last_step')
                ->default(1)
                ->after('completion_percentage');



            $table->timestamp('draft_saved_at')
                ->nullable()
                ->after('last_step');


        });

    }



    public function down(): void
    {

        Schema::table('manuscripts', function (Blueprint $table) {

            $table->dropColumn([
                'completion_percentage',
                'last_step',
                'draft_saved_at'
            ]);

        });

    }

};