<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    public $timestamps = false;
    protected $primaryKey = "code";
    protected $table = "branch";
    protected $fillable = ['name', 'address_name', 'status', 'slug'];
    public function Stock()
    {
        return $this->hasMany(Products_Stock::class);
    }
    public function User()
    {
        return $this->hasOne(User::class);
    }
}
