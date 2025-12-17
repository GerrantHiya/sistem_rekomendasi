<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $table = 'order_items';
    protected $primaryKey = 'ID_Order_Items';
    public $timestamps = false;

    protected $fillable = [
        'ID_Orders',
        'ID_Variant',
        'Status',
        'Shipping_Address',
        'Discount',
        'Subtotal',
        'Delivery_Cost',
        'Total'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'ID_Orders', 'ID_Orders');
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'ID_Variant', 'ID_Variants');
    }
}
