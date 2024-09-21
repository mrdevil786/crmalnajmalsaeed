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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->string('type')->default('invoice');  // Can be 'invoice' or 'quotation'
            $table->decimal('total_amount', 10, 2);
            $table->decimal('discount', 10, 2)->nullable();  // Optional discount
            $table->decimal('vat', 10, 2);                  // VAT percentage or amount
            $table->decimal('final_amount', 10, 2);         // Total after VAT and discount
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
