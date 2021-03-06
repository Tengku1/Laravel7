<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Products_Stock extends Model
{
    protected $table = "products_stock";
    public $timestamp = false;
    const UPDATED_AT = "modified_at";
    const CREATED_AT = "created_at";
    protected $fillable = ['product_id', 'branch_code', 'buy_price', 'qty'];
    public function Branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function Product()
    {
        return $this->belongsTo(Product::class);
    }
}
