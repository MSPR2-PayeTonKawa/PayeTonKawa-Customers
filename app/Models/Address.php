<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

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

    public function billingCompanies() {
        return $this->hasMany(Company::class, 'billing_address_id');
    }

    public function shippingCompanies() {
        return $this->hasMany(Company::class, 'shipping_address_id');
    }
}
