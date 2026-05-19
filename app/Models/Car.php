<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = [
        'company_id',
        'car_name',
        'brand',
        'model',
        'car_type',
        'fuel_type',
        'price_per_day',
        'wedding_price',
        'car_number',
        'status',
        'image',
    ];
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function bookings()
{
    return $this->hasMany(CarBooking::class);
}

}



