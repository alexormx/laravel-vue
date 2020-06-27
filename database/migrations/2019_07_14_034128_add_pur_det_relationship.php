<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPurDetRelationship extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchdets', function (Blueprint $table) {
            $table->bigInteger('purch_id')->unsigned()->change();
            $table->string('product_id')->unsigned()->change();
            $table->foreign('purch_id')->references('id')->on('purches');
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
        Schema::table('purchdets', function (Blueprint $table) {
            $table->dropForeign(['purch_id']);
            $table->dropForeign(['product_id']);
        });
    }
}
