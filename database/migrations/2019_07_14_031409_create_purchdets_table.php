<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchdetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchdets', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('purch_id');
            $table->Integer('poline');
            $table->string('product_id');
            $table->Integer('quantity');
            $table->decimal('unitcost',8,2);
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
        Schema::dropIfExists('purchdets');
    }
}
