<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Consumption extends Authenticatable
{
    use HasApiTokens,Notifiable;
    protected $table="consumption_tbl";

    protected  $primaryKey  = 'consumption_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'raw_id', 'unit', 'stock','user_id'
    ];

    public function raw()
    {
        return $this->belongsTo('App\Raw','raw_id','raw_id');
    }

    public function users()
    {
        return $this->belongsTo('App\User','user_id','id');
    }

    public function products()
    {
        return $this->belongsTo('App\Product','product_id','id');
    }
}
