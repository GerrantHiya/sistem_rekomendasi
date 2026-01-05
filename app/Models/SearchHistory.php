<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SearchHistory extends Model
{
    protected $table = 'search_histories';
    protected $primaryKey = 'ID_SearchHistory';
    public $timestamps = false;

    protected $fillable = [
        'ID_Customers',
        'search_query',
        'ID_Categories',
        'ID_Brand',
        'searched_at'
    ];

    protected $casts = [
        'searched_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'ID_Customers', 'ID_Customers');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'ID_Categories', 'ID_Categories');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'ID_Brand', 'ID_Brand');
    }
}
