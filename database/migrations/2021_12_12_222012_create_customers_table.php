<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('customers', function (Blueprint $table) {
        //     $table->id();
        //     $table->String('name');
        //     $table->String('company_name')->nullable();
        //     $table->String('company_regis')->nullable();
        //     $table->String('street_address')->nullable();
        //     $table->String('city')->nullable();
        //     $table->String('tel1')->nullable();
        //     $table->String('tel2')->nullable();
        //     $table->String('email')->nullable();
        //     $table->String('bank_details')->nullable();
        //     $table->integer('status')->default(1);
        //     $table->integer('admin_id');
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
        Schema::dropIfExists('customers');
    }
}
