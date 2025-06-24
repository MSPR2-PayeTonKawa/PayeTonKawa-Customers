<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Addresses extends Model
{
    protected $fillable = [
        'number',
        'number_complement',
        'way_name',
        'way_type',
        'city',
        'zip_code',
        'country',
        'latitude',
        'longitude'
    ];

    protected $attributes = [
        'number_complement' => null,
        'latitude' => null,
        'longitude' => null
    ];

    public function billingCompanies() {
        return $this->hasMany(Companies::class, 'billing_address_id');
    }

    public function shippingCompanies() {
        return $this->hasMany(Companies::class, 'shipping_address_id');
    }
}
