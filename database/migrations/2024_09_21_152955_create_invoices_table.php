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
            $table->string('invoice_number')->unique();
            $table->enum('type', ['invoice', 'quote'])->default('invoice');
            $table->date('issue_date');
            $table->date('due_date')->nullable();
            $table->decimal('vat_percentage', 5, 2)->default(15);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount', 10, 2)->nullable();
            $table->decimal('vat_amount', 10, 2);
            $table->decimal('total', 10, 2);
            $table->text('notes')->nullable();
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
