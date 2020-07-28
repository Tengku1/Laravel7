<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'slug', 'sell_price', 'status'];
    public $timestamp = false;
    const UPDATED_AT = "modified_at";
    const CREATED_AT = "created_at";
    public function Stock()
    {
        return $this->hasMany(Products_Stock::class);
    }
}
