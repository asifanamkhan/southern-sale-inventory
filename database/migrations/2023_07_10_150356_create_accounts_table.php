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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->integer('type')->comment('1-petty cash, 2-bank');
            $table->string('bank')->nullable();
            $table->string('branch')->nullable();
            $table->string('account_no')->nullable();
            $table->string('name')->nullable();
            $table->double('opening_balance')->nullable();
            $table->integer('status')->default(1);
            $table->text('description')->nullable();
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
        Schema::dropIfExists('accounts');
    }
};
