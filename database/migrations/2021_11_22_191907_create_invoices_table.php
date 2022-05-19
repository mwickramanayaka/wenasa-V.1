<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_code');
            $table->string('referance')->nullable();
            $table->integer('order_type_id')->default(1);
            $table->integer('invoice_type_id')->default(1);
            $table->integer('merchant_id')->default(1);
            $table->integer('service_charge_id')->default(1);
            $table->integer('emp_id')->nullable();
            $table->integer('administration_id');
            $table->integer('warehouse_id');
            $table->integer('vat_id');
            $table->double('total');
            $table->double('discount')->default(0);
            $table->double('sc_value');
            $table->double('vat_value')->default(0);
            $table->double('invoice_type_value');
            $table->double('net_total');
            $table->double('paid_amount')->default(0);
            $table->double('balance_amount')->default(0);
            $table->text('remark')->nullable();
            $table->text('billing_to')->nullable();
            $table->text('billing_address')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
