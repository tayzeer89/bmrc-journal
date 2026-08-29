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

            $table->foreignId('manuscript_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('invoice_no')->unique();

            $table->string('fee_type');

            $table->decimal('amount', 15, 2);

            $table->string('currency')->default('BDT');

            $table->date('invoice_date');

            $table->date('payment_deadline')->nullable();

            $table->string('payment_method')->nullable();
            $table->string('payment_gateway')->nullable();

            $table->string('transaction_id')->nullable()->index();

            $table->timestamp('payment_date')->nullable();

            $table->string('payer_name')->nullable();
            $table->string('payer_mobile')->nullable();

            $table->string('payment_status')->default('pending');

            $table->string('verification_status')->default('pending');

            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('verified_at')->nullable();

            $table->text('remarks')->nullable();

            $table->timestamps();
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
