<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductReview extends Model
{
    protected $table = 'product_reviews';
    protected $primaryKey = 'ID_Reviews';

    protected $fillable = [
        'ID_Products',
        'ID_Customers',
        'ID_Orders',
        'rating',
        'title',
        'review',
        'is_verified_purchase',
        'is_approved'
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_verified_purchase' => 'boolean',
        'is_approved' => 'boolean'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'ID_Products', 'ID_Products');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'ID_Customers', 'ID_Customers');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'ID_Orders', 'ID_Orders');
    }

    /**
     * Scope for approved reviews only
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope for verified purchases only
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified_purchase', true);
    }
}
