<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Stocklog extends Authenticatable
{
    use HasApiTokens,Notifiable;
    protected $table="raw_stock_log";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'raw_id', 'price', 'unit', 'stock','log_type','operation','user_id', 'raw_name'
    ];

    public function raw()
    {
        return $this->belongsTo('App\Raw','raw_id','raw_id');
    }

    public function users()
    {
        return $this->belongsTo('App\User','user_id','id');
    }

}
