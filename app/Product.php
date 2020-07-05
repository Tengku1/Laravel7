<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'slug', 'sell_price', 'status'];
    public function Stock()
    {
        return $this->hasOne(Products_Stock::class);
    }
}
