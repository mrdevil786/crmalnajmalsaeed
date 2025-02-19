<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVatReturnsTable extends Migration
{
    public function up()
    {
        Schema::create('vat_returns', function (Blueprint $table) {
            $table->id();
            $table->date('period_from');
            $table->date('period_to');
            $table->decimal('total_sales', 15, 2);
            $table->decimal('total_purchases', 15, 2);
            $table->decimal('output_vat', 15, 2);
            $table->decimal('input_vat', 15, 2);
            $table->decimal('net_vat_payable', 15, 2);
            $table->string('status')->default('draft');
            $table->text('notes')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vat_returns');
    }
}
