<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Retailproduct extends Model
{
    //
    protected $table = 'retail_product';
    protected  $primaryKey  = 'retail_product_id';

    protected $fillable = [ 'product_id', 'quantity', 'unity','retail_id','user_id','product_status', 'created_at', 'updated_at','product_retail_id'];


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
        return $this->belongsTo('App\User','retail_id','id');
    }

    public function proretailslogs()
    {
        return $this->belongsTo('App\User','product_retail_id','product_retail_assign_log_id');
    }
}
