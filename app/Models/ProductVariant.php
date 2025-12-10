<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Services\IdHasher;

class ProductVariant extends Model
{
    protected $table = 'product_variants';
    protected $primaryKey = 'ID_Variants';
    public $timestamps = false;

    protected $fillable = [
        'variant_sku',
        'id_CatSize',
        'color',
        'price',
        'stock_qty',
        'weight_gram',
        'ID_Product',
        'ID_Size'
    ];

    public function getHashedIdAttribute(): string
    {
        return IdHasher::encode($this->ID_Variants);
    }

    public static function findByHash($hash)
    {
        $id = IdHasher::decode($hash);
        return $id ? static::find($id) : null;
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'ID_Product', 'ID_Products');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'ID_Variant', 'ID_Variants');
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(Cart::class, 'ID_Variant', 'ID_Variants');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'ID_Variant', 'ID_Variants');
    }

    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }
}

