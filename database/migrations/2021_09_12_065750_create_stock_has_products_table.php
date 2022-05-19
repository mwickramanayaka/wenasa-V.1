<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockHasProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_has_products', function (Blueprint $table) {
            $table->id();
            $table->integer('stock_id');
            $table->integer('product_id');
            $table->double('in_price');
            $table->double('out_price')->nullable();
            $table->double('qty');
            $table->double('vat_id');
            $table->double('vat_value');
            $table->date('exp_date')->nullable();
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
        Schema::dropIfExists('stock_has_products');
    }
}
