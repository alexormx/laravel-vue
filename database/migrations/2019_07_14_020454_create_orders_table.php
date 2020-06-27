<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->Integer('id_cus');
             $table->date('saledt');
            $table->integer('salemethod');
            $table->integer('paymethod');
            $table->integer('shipmethod');
            $table->integer('tracking');
            $table->decimal('shipcost',8,2);
            $table->decimal('prodcost',8,2);
            $table->decimal('totalcost',8,2);
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
        Schema::dropIfExists('orders');
    }
}
