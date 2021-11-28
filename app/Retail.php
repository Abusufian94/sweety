<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Retail extends Model
{
    protected $table = 'retail_tbl';
    protected $guarded = [];
    protected $primaryKey = 'retail_id';

      protected $fillable = [
        'retail_name',
        'status',
        'street_name'
       
	];
		protected $hidden = [
        'created_at', 'updated_at'
    ];
	

}
