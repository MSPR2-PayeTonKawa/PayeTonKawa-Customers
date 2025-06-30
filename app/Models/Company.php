<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'billing_address_id',
        'shipping_address_id'
    ];

    public function customers() {
        return $this->hasMany(Customer::class);
    }

    public function billingAddress() {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }
    public function shippingAddress() {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }
}
