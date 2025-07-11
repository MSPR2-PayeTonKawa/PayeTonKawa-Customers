<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'username',
        'last_name',
        'first_name',
        'email',
        'phone',
        'company_id',
    ];

    protected $hidden = [
        'is_active',
    ];

    public function company() {
        return $this->belongsTo(Company::class);
    }
}
