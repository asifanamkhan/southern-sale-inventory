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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->integer('supplier_id');
            $table->string('date');
            $table->string('document')->nullable();
            $table->integer('status')->comment('1-complete,2-pending');
            $table->integer('payment_status')->comment('1-paid,2-partial paid,3-unpaid');
            $table->double('vat')->default(0);
            $table->double('discount')->default(0);
            $table->double('shipping_charge')->default(0);
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
        Schema::dropIfExists('purchases');
    }
};
