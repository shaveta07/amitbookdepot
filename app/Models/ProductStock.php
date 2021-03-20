<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * App\Models\ProductStock
 *
 * @property int $id
 * @property int $product_id
 * @property string $variant
 * @property string $sku
 * @property float $price
 * @property string|null $variant_image
 * @mixin \Eloquent
 */
class ProductStock extends Model
{
    //
    public function product(){
    	return $this->belongsTo(Product::class);
    }
}
