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
        Schema::create('ethical_information', function (Blueprint $table) {
            $table->id();

            $table->foreignId('manuscript_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();

            $table->boolean('human_participants')->default(false);
            $table->boolean('animal_study')->default(false);

            $table->boolean('ethical_approval_required')->default(false);

            $table->string('ethics_committee_name')->nullable();
            $table->string('institution')->nullable();
            $table->string('approval_number')->nullable();
            $table->date('approval_date')->nullable();

            $table->boolean('informed_consent_obtained')->nullable();

            $table->string('consent_type')->nullable();

            $table->boolean('clinical_trial')->default(false);

            $table->string('trial_registration_number')->nullable();
            $table->string('trial_registry')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ethical_information');
    }
};
