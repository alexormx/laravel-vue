<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderProductRelationship extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orderdets', function (Blueprint $table) {
            $table->string('product_id')->unsigned()->change();
            $table->foreign('product_id')->references('id')->on('products');
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
            $table->dropForeign(['product_id']);
        });

        Schema::table('orderdets', function (Blueprint $table) {
            $table->bigInteger('product_id')->change();
        });
    }
}
