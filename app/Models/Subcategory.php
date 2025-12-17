<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subcategory extends Model
{
    protected $table = 'subcategories';
    protected $primaryKey = 'ID_SubCategories';
    public $timestamps = false;

    protected $fillable = ['name', 'ID_Categories'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'ID_Categories', 'ID_Categories');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'ID_SubCategories', 'ID_SubCategories');
    }
}
