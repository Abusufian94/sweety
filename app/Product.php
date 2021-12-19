<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Product extends Authenticatable
{
    use HasApiTokens,Notifiable;
    protected $table="product";

    protected  $primaryKey  = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'product_unit', 'product_quantity','product_price','product_name','user_id','product_image',
    ];

    public function users()
    {
        return $this->belongsTo('App\User','user_id','id');
    }
}
