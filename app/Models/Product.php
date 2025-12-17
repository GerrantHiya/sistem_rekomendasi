<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Services\IdHasher;

class Product extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'ID_Products';
    public $timestamps = false;

    protected $fillable = [
        'Name',
        'SKU',
        'ID_Brand',
        'ID_Gender',
        'ID_Categories',
        'ID_SubCategories',
        'Description'
    ];

    /**
     * Get hashed ID for URL
     */
    public function getHashedIdAttribute(): string
    {
        return IdHasher::encode($this->ID_Products);
    }

    /**
     * Find product by hashed ID
     */
    public static function findByHash($hash)
    {
        $id = IdHasher::decode($hash);
        if ($id === null) {
            return null;
        }
        return static::find($id);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'ID_Brand', 'ID_Brand');
    }

    public function gender(): BelongsTo
    {
        return $this->belongsTo(Gender::class, 'ID_Gender', 'ID_Gender');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'ID_Categories', 'ID_Categories');
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class, 'ID_SubCategories', 'ID_SubCategories');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class, 'ID_Product', 'ID_Products');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class, 'ID_Products', 'ID_Products');
    }

    /**
     * Get approved reviews only
     */
    public function approvedReviews(): HasMany
    {
        return $this->hasMany(ProductReview::class, 'ID_Products', 'ID_Products')
            ->where('is_approved', true);
    }

    /**
     * Get average rating
     */
    public function getAverageRatingAttribute(): float
    {
        return round($this->approvedReviews()->avg('rating') ?? 0, 1);
    }

    /**
     * Get review count
     */
    public function getReviewCountAttribute(): int
    {
        return $this->approvedReviews()->count();
    }

    /**
     * Get total purchases count
     */
    public function getTotalPurchasesAttribute(): int
    {
        return OrderItem::join('product_variants', 'order_items.ID_Variant', '=', 'product_variants.ID_Variants')
            ->where('product_variants.ID_Product', $this->ID_Products)
            ->count();
    }

    /**
     * Get TF-IDF text content for similarity calculation
     */
    public function getTfIdfContent(): string
    {
        $parts = [];
        
        // Include product name (weighted more by repeating)
        $parts[] = $this->Name;
        $parts[] = $this->Name;
        $parts[] = $this->Name;
        
        // Include description
        if ($this->Description) {
            $parts[] = $this->Description;
        }
        
        // Include brand name
        if ($this->brand) {
            $parts[] = $this->brand->name;
            $parts[] = $this->brand->name;
        }
        
        // Include category
        if ($this->category) {
            $parts[] = $this->category->name;
            $parts[] = $this->category->name;
        }
        
        // Include subcategory
        if ($this->subcategory) {
            $parts[] = $this->subcategory->name;
            $parts[] = $this->subcategory->name;
        }
        
        // Include gender
        if ($this->gender) {
            $parts[] = $this->gender->name;
        }
        
        // Include variant colors
        foreach ($this->variants as $variant) {
            if ($variant->color) {
                $parts[] = $variant->color;
            }
        }
        
        return strtolower(implode(' ', $parts));
    }

    /**
     * Get the minimum price from all variants
     */
    public function getMinPriceAttribute()
    {
        return $this->variants->min('price') ?? 0;
    }

    /**
     * Get the maximum price from all variants
     */
    public function getMaxPriceAttribute()
    {
        return $this->variants->max('price') ?? 0;
    }

    /**
     * Get total stock from all variants
     */
    public function getTotalStockAttribute()
    {
        return $this->variants->sum('stock_qty') ?? 0;
    }

    /**
     * Get the first image from variants
     */
    public function getFirstImageAttribute()
    {
        $variant = $this->variants()->with('images')->first();
        if ($variant && $variant->images->count() > 0) {
            return $variant->images->first()->image;
        }
        return null;
    }
}

