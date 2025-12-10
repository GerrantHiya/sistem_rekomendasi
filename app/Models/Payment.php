<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $table = 'payments';
    protected $primaryKey = 'ID_Payments';
    public $timestamps = false;

    protected $fillable = [
        'ID_Order',
        'Paid_at',
        'Status'
    ];

    protected $casts = [
        'Paid_at' => 'datetime'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'ID_Order', 'ID_Orders');
    }
}
