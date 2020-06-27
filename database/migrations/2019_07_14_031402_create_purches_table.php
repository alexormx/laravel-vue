<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purches', function (Blueprint $table) {
            $table->BigInteger('id')->unique()->unsigned();
            $table->string('product_id');
            $table->string('user_id');
            $table->date('purdt');
            $table->string('currency');
            $table->decimal('exchange',6,2);
            $table->decimal('taxcost',10,2);
            $table->decimal('shipcost',10,2);
            $table->Integer('quantity');
            $table->decimal('unitcost', 6, 2);
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
        Schema::dropIfExists('purches');
    }
}
