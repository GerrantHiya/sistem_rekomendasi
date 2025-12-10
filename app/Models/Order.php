<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'ID_Orders';
    public $timestamps = false;

    protected $fillable = [
        'ID_Customers',
        'place_at',
        'Status',
        'Shipping_Address',
        'Discount',
        'Subtotal',
        'Delivery_Cost',
        'Total'
    ];

    protected $casts = [
        'place_at' => 'datetime'
    ];

    const STATUS_PENDING = 0;
    const STATUS_PROCESSING = 1;
    const STATUS_SHIPPED = 2;
    const STATUS_DELIVERED = 3;
    const STATUS_CANCELLED = 4;

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'ID_Customers', 'ID_Customers');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'ID_Orders', 'ID_Orders');
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class, 'ID_Order', 'ID_Orders');
    }

    public function shipment(): HasOne
    {
        return $this->hasOne(Shipment::class, 'ID_Orders', 'ID_Orders');
    }

    public function getStatusNameAttribute()
    {
        return match($this->Status) {
            self::STATUS_PENDING => 'Pending',
            self::STATUS_PROCESSING => 'Processing',
            self::STATUS_SHIPPED => 'Shipped',
            self::STATUS_DELIVERED => 'Delivered',
            self::STATUS_CANCELLED => 'Cancelled',
            default => 'Unknown'
        };
    }

    public function getStatusBadgeClassAttribute()
    {
        return match($this->Status) {
            self::STATUS_PENDING => 'warning',
            self::STATUS_PROCESSING => 'info',
            self::STATUS_SHIPPED => 'primary',
            self::STATUS_DELIVERED => 'success',
            self::STATUS_CANCELLED => 'danger',
            default => 'secondary'
        };
    }
}
