<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'company_id',
        'name',
        'category',
        'number_plate',
        'self_drive_allowed',
        'included_km',
        'extra_km_price',
        'is_active'
    ];

    public function company() {
        return $this->belongsTo(Company::class);
    }
}

