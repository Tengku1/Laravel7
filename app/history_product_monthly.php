<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class history_product_monthly extends Model
{
    protected $table = "history_product_monthly";
    protected $fillable = ['product_id', 'branch_code', 'buy_price', 'qty'];
}
