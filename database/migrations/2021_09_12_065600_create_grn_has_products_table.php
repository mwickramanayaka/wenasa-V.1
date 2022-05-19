<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGrnHasProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grn_has_products', function (Blueprint $table) {
            $table->id();
            $table->integer('grn_id');
            $table->integer('product_id');
            $table->double('unit_price');
            $table->double('request_qty');
            $table->double('in_qty');
            $table->double('total');
            $table->integer('vat_id')->default(1);
            $table->double('net_total');
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
        Schema::dropIfExists('grn_has_products');
    }
}
