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

            // $table->foreignId('technical_check_id')
            //     ->constrained('technical_checks')
            //     ->cascadeOnDelete()
            //     ->index();

            $table->unsignedBigInteger('technical_check_id');

            $table->foreign('technical_check_id', 'technical_check_items_technical_check_id_fk')
                ->references('id')
                ->on('technical_checks')
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
            ])
                ->default('pending')
                ->index();


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


            /*
            |--------------------------------------------------------------------------
            | Timestamps
            |--------------------------------------------------------------------------
            */

            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | Prevent Duplicate Checklist Key
            |--------------------------------------------------------------------------
            */

            $table->unique([
                'technical_check_id',
                'check_key',
            ]);
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('technical_check_items');
    }
};
