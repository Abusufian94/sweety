<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RetailUser extends Model
{
    protected $table = 'retail_user';
    protected $guarded = [];
    protected $primaryKey = 'retail_user_id';

      protected $fillable = [
        'retail_id',
        'user_id',
       
	];
		protected $hidden = [
        'created_at', 'updated_at'
    ];
	

}
