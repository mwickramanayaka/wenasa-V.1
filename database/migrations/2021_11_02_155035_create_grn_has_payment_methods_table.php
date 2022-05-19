<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGrnHasPaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grn_has_payment_methods', function (Blueprint $table) {
            $table->id();
            $table->integer('grn_id');
            $table->integer('payment_methods_id');
            $table->double('amount');
            $table->double('balance');
            $table->string('ref_number')->nullable();
            $table->string('holder_name')->nullable();
            $table->string('card_number')->nullable();
            $table->string('card_type')->nullable();
            $table->string('cheque')->nullable();
            $table->tinyInteger('status');
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
        Schema::dropIfExists('grn_has_payment_methods');
    }
}
