<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('technical_check_items', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Technical Check
            |--------------------------------------------------------------------------
            */

            $table->foreignId('technical_check_id')
                ->constrained('technical_checks')
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Checklist Information
            |--------------------------------------------------------------------------
            */

            $table->string('check_key');

            $table->string('check_name');

            $table->unsignedInteger('sort_order')
                ->default(0);


            /*
            |--------------------------------------------------------------------------
            | Result
            |--------------------------------------------------------------------------
            */

            $table->enum('result', [
                'pending',
                'pass',
                'fail',
                'na',
            ])->default('pending');


            /*
            |--------------------------------------------------------------------------
            | Officer Comment
            |--------------------------------------------------------------------------
            */

            $table->text('comment')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | Checked By
            |--------------------------------------------------------------------------
            */

            $table->foreignId('checked_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('checked_at')
                ->nullable();


            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Prevent duplicate checklist key
            |--------------------------------------------------------------------------
            */

            $table->unique([
                'technical_check_id',
                'check_key'
            ]);

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('technical_check_items');
    }
};