<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceHasProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_has_products', function (Blueprint $table) {
            $table->id();
            $table->integer('invoice_id');
            $table->double('unit_price');
            $table->double('qty');
            $table->double('total');
            $table->double('discount');
            $table->integer('vat_id');
            $table->double('vat_value');
            $table->double('net_total');
            $table->integer('shp_id');
            $table->integer('chp_id')->nullable();
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
        Schema::dropIfExists('invoice_has_products');
    }
}
