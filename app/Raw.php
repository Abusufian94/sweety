<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Raw extends Authenticatable
{
    use HasApiTokens,Notifiable;
    protected $table="raw_tbl";

    protected  $primaryKey  = 'raw_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'raw_name', 'unit', 'stock','price'
    ];
}
