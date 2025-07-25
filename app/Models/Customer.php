<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'username', // sera à supprimer car inutile dans un CRM
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

    public function getFullName(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function hasCompany(): bool
    {
        return !is_null($this->company_id);
    }
}
