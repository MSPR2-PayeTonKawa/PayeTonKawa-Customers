<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    protected $fillable = [
        'username',
        'last_name',
        'first_name',
        'email',
        'phone',
        'company_id',
    ];

    protected $hidden = [
        'hashed_salted_password',
        'password_updated_at',
        'created_at',
        'is_active',
    ];

    protected $attributes = [
        'phone' => null,
        'is_active' => true,
        'company_id' => null,
        'password_updated_at' => null,
    ];

    public function company() {
        return $this->belongsTo(Companies::class);
    }
}
