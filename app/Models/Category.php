<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'ID_Categories';
    public $timestamps = false;

    protected $fillable = ['name'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'ID_Categories', 'ID_Categories');
    }

    public function subcategories(): HasMany
    {
        return $this->hasMany(Subcategory::class, 'ID_Categories', 'ID_Categories');
    }
}
