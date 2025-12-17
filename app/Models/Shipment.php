<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shipment extends Model
{
    protected $table = 'Shipments';
    protected $primaryKey = 'ID_Shipments';
    public $timestamps = false;

    protected $fillable = [
        'ID_Orders',
        'Tracking_Number',
        'Status'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'ID_Orders', 'ID_Orders');
    }
}
