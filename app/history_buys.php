<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class history_buys extends Model
{
    protected $fillable = ['modified_user', 'branch_code', 'has_finished'];
    public $timestamp = false;
    protected $table = "history_buy";
    const UPDATED_AT = "modified_at";
    const CREATED_AT = "created_at";
}
