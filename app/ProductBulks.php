<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductBulks extends Model
{
    protected $fillable=[
        'product_id', 'product_stock_id', 'customertype', 'overideprice', 'qtyrange' 
    ];

    public function product(){
    	return $this->belongsTo(Product::class);
    }
    public function stocks(){
        return $this->hasMany(ProductStock::class);
    }
    public function customer(){
        return $this->hasMany(Customer::class);
        }
}
