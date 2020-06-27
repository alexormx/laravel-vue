<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderOrderdetsRelationship extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orderdets', function (Blueprint $table) {
            $table->bigInteger('order_id')->unsigned()->change();
            $table->foreign('order_id')->references('id')->on('orders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orderdets', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
        });

        Schema::table('orderdets', function (Blueprint $table) {
            $table->bigInteger('order_id')->change();
        });
    }
}
