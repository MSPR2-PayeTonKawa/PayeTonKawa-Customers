<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Companies extends Model
{
    public function customers() {
        return $this->hasMany(Customers::class);
    }

    public function billingAddress() {
        return $this->belongsTo(Addresses::class, 'billing_address_id');
    }
    public function shippingAddress() {
        return $this->belongsTo(Addresses::class, 'shipping_address_id');
    }
}
