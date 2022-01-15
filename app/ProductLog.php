<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductLog extends Model
{
    //
    protected $table = 'product_log';
    protected $fillable = ['product_log_id', 'product_id', 'quantity', 'user_id', 'created_at', 'updated_at'];
}
