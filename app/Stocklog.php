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
        'raw_id', 'unit', 'stock','price','log_type','operation','user_id'
    ];
}
