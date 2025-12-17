<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Services\IdHasher;

class ProductImage extends Model
{
    protected $table = 'product_image';
    protected $primaryKey = 'ID_Image';
    public $timestamps = false;

    protected $fillable = ['image', 'ID_Variant'];

    public function getHashedIdAttribute(): string
    {
        return IdHasher::encode($this->ID_Image);
    }

    public static function findByHash($hash)
    {
        $id = IdHasher::decode($hash);
        return $id ? static::find($id) : null;
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'ID_Variant', 'ID_Variants');
    }

    public function getUrlAttribute()
    {
        return asset('storage/products/' . $this->image);
    }
}

