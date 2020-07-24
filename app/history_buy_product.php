<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class history_buy_product extends Model
{
    public $timestamps = false;
    protected $table = "history_buy_product";
    protected $fillable = ['history_buy', 'product_id', 'qty', 'buy_price'];
}
