<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGrnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grns', function (Blueprint $table) {
            $table->id();
            $table->string('grn_code');
            $table->string('po_ref')->nullable();
            $table->double('total');
            $table->integer('supplier_id')->nullable();
            $table->integer('grn_type_id');
            $table->integer('vat_id');
            $table->integer('warehouse_id');
            $table->text('remark')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->integer('user_id');
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
        Schema::dropIfExists('grns');
    }
}
