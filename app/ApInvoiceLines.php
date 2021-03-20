<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApInvoiceLines extends Model
{
    public $timestamps = false;
    protected $table = 'ap_invoice_lines'; 
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
