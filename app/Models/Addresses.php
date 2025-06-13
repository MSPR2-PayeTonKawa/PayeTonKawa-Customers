<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Addresses extends Model
{
    public function billingCompanies() {
        return $this->hasMany(Companies::class, 'billing_address_id');
    }

    public function shippingCompanies() {
        return $this->hasMany(Companies::class, 'shipping_address_id');
    }
}
