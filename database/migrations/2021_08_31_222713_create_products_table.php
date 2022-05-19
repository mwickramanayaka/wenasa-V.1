<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('lang1_name');
            $table->string('lang2_name')->nullable();
            $table->string('lang3_name')->nullable();
            $table->double('default_price');
            $table->longText('description')->nullable();
            $table->double('low_stock_alert_qty')->default(5);
            $table->integer('product_category_id');
            $table->integer('measurement_id');
            $table->integer('brand_id')->nullable();
            $table->integer('product_type_id')->nullable();
            $table->integer('status')->default(3);
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
        Schema::dropIfExists('products');
    }
}
