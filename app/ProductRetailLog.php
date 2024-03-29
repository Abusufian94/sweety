<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductRetailLog extends Model
{
    //
    protected $table = 'product_retail_assign_log';
    protected  $primaryKey  = 'product_retail_assign_log_id';
    protected $fillable = [ 'product_id', 'quantity', 'unity','retail_id','user_id','status', 'created_at', 'updated_at'];


    public function products()
    {
        return $this->belongsTo('App\Product','product_id','id');
    }

    public function users()
    {
        return $this->belongsTo('App\User','user_id','id');
    }

    public function retails()
    {
        return $this->belongsTo('App\Retail','retail_id','retail_id');
    }
}
