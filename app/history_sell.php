<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class history_sell extends Model
{
    protected $table = "history_sell";
    public $timestamp = false;
    const UPDATED_AT = "modified_at";
    const CREATED_AT = "created_at";
    protected $fillable = ['modified_user', 'branch_code'];
}
