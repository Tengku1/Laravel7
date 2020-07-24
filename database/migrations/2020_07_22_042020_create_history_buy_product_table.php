<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryBuyProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_buy_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("history_buy");
            $table->unsignedBigInteger("product_id");
            $table->integer("qty");
            $table->float("buy_price");
            $table->foreign("history_buy")->references("id")->on("history_buy");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('history_buy_product');
    }
}
