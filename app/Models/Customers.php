<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    public function company() {
        return $this->belongsTo(Companies::class);
    }
}
