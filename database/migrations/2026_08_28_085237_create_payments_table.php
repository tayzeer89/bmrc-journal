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
        Schema::create('payments', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Manuscript
            |--------------------------------------------------------------------------
            */

            $table->foreignId('manuscript_id')
                ->constrained('manuscripts')
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Invoice
            |--------------------------------------------------------------------------
            */

            $table->string('invoice_no')
                ->unique();

            $table->string('fee_type');

            $table->decimal('amount', 15, 2);

            $table->string('currency', 3)
                ->default('BDT');

            $table->date('invoice_date');

            $table->date('payment_deadline')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | Payment Request
            |--------------------------------------------------------------------------
            */

            $table->timestamp('sent_to_author_at')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | Author Payment Information
            |--------------------------------------------------------------------------
            */

            $table->string('payment_method')
                ->nullable();

            $table->string('payment_gateway')
                ->nullable();

            $table->string('transaction_id')
                ->nullable()
                ->index();

            $table->timestamp('payment_date')
                ->nullable();

            $table->string('payer_name')
                ->nullable();

            $table->string('payer_mobile', 30)
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | Payment Status
            |--------------------------------------------------------------------------
            |
            | pending   = Invoice created / waiting for author
            | submitted = Author submitted payment information
            | paid      = Payment verified
            | rejected  = Optional final rejection
            | cancelled = Payment cancelled
            |
            */

            $table->enum('payment_status', [
                'pending',
                'submitted',
                'paid',
                'rejected',
                'cancelled',
            ])
                ->default('pending')
                ->index();


            /*
            |--------------------------------------------------------------------------
            | Verification Status
            |--------------------------------------------------------------------------
            |
            | pending  = Waiting for staff verification
            | verified = Payment verified
            | rejected = Staff rejected payment
            |
            */

            $table->enum('verification_status', [
                'pending',
                'verified',
                'rejected',
            ])
                ->default('pending')
                ->index();


            /*
            |--------------------------------------------------------------------------
            | Verification
            |--------------------------------------------------------------------------
            */

            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('verified_at')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | Staff / Invoice Creation
            |--------------------------------------------------------------------------
            */

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Remarks
            |--------------------------------------------------------------------------
            */

            $table->text('remarks')
                ->nullable();

            $table->text('verification_notes')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | Timestamps
            |--------------------------------------------------------------------------
            */

            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | Additional Indexes
            |--------------------------------------------------------------------------
            */

            $table->index([
                'manuscript_id',
                'payment_status',
            ]);

            $table->index([
                'payment_status',
                'verification_status',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};