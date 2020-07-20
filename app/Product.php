<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'slug', 'sell_price', 'status'];
    public function Stock()
    {
        return $this->hasMany(Products_Stock::class);
    }
}
