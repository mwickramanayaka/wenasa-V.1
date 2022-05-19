<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceHasCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('invoice_has_customers', function (Blueprint $table) {
        //     $table->id();
        //     $table->integer('invoice_id');
        //     $table->integer('customer_id');
        //     $table->integer('invoice_type_id');
        //     $table->date('pay_done_date')->default(DB::raw('CURRENT_TIMESTAMP'));
        //     $table->integer('status');
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_has_customers');
    }
}
