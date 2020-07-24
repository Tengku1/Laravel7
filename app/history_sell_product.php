<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class history_sell_product extends Model
{
    public $timestamps = false;
    protected $table = "history_sell_product";
    protected $fillable = ['history_sell', 'product_id', 'qty', 'buy_price', 'sell_price'];
}
