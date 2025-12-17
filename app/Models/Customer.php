<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Authenticatable
{
    protected $table = 'customers';
    protected $primaryKey = 'ID_Customers';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'email',
        'password_hash',
        'phone_number',
        'last_login',
        'address',
        'city',
        'province',
        'postcode'
    ];

    protected $hidden = [
        'password_hash'
    ];

    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    public function cart(): HasMany
    {
        return $this->hasMany(Cart::class, 'ID_Customers', 'ID_Customers');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'ID_Customers', 'ID_Customers');
    }
}
