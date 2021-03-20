<?php
namespace App\Models;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
/**
 * App\Models\Product
 *
 * @property int $id
 * @property int $product_id
 * @property int $product_stock_id
 * @property string $customertype
 * @property float $overideprice
 * @property int $qtyrange
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *  * @mixin \Eloquent
 */
class ProductBulks extends Model
{
    protected $fillable=[
        'product_id', 'product_stock_id', 'customertype', 'overideprice', 'qtyrange' 
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('published', function (Builder $builder) {
            $builder->where('published', 1);
        });
    }

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
?>