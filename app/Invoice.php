<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Log;
class Invoice extends Model
{
    //
    protected $table = 'invoice';
    protected $appends = ['invoice_url'];
    public function getInvoiceUrlAttribute() {
    	
        return asset('invoices/'.$this->file);
    }

}
