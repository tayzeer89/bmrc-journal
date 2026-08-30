<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{

    public function up(): void
    {

        Schema::create('manuscript_details', function (Blueprint $table) {


            $table->id();



            $table->foreignId('manuscript_id')
                ->constrained()
                ->cascadeOnDelete();



            /*
            Scientific Information
            */

            $table->longText('background')
                ->nullable();


            $table->longText('objective')
                ->nullable();


            $table->longText('methods')
                ->nullable();


            $table->longText('results')
                ->nullable();


            $table->longText('conclusion')
                ->nullable();



            /*
            Trial Information
            */

            $table->string('trial_registration_number')
                ->nullable();


            $table->string('trial_registration_organization')
                ->nullable();



            /*
            Study Information
            */

            $table->string('study_design')
                ->nullable();


            $table->date('study_start_date')
                ->nullable();


            $table->date('study_end_date')
                ->nullable();


            $table->string('study_location')
                ->nullable();


            $table->integer('sample_size')
                ->nullable();




            /*
            Funding
            */

            $table->string('funding_source')
                ->nullable();



            /*
            Ethics
            */

            $table->boolean('ethical_approval_available')
                ->default(false);


            $table->string('ethical_approval_number')
                ->nullable();


            $table->date('ethical_approval_date')
                ->nullable();



            $table->timestamps();


        });

    }




    public function down(): void
    {

        Schema::dropIfExists('manuscript_details');

    }

};