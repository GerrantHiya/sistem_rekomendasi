<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    protected $table = 'cart';
    protected $primaryKey = 'ID_Cart';
    public $timestamps = false;

    protected $fillable = [
        'ID_Customers',
        'ID_Variant',
        'unit_price',
        'quantity'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'ID_Customers', 'ID_Customers');
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'ID_Variant', 'ID_Variants');
    }

    public function getSubtotalAttribute()
    {
        return $this->unit_price * $this->quantity;
    }
}
