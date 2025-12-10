<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gender extends Model
{
    protected $table = 'gender';
    protected $primaryKey = 'ID_Gender';
    public $timestamps = false;

    protected $fillable = ['name', 'code'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'ID_Gender', 'ID_Gender');
    }
}
