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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('date');
            $table->integer('account_id');
            $table->integer('type')
                ->comment('1-purchase, 2-order, 3-expense, 4-deposit, 5-withdraw, 6-opening');
            $table->integer('relation_id');
            $table->double('debit_amount')->nullable();
            $table->double('credit_amount')->nullable();
            $table->double('difference');
            $table->integer('status')->default(1);
            $table->text('narration')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
