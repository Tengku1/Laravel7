<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistorySellProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_sell_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("history_sell");
            $table->unsignedBigInteger("product_id");
            $table->unsignedBigInteger("branch_code");
            $table->integer("qty");
            $table->float("buy_price");
            $table->float("sell_price");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('history_sell_product');
    }
}
