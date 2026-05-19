<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CarBooking;

class Customer extends Model
{

protected $fillable = [
    'name',
    'phone',
    'email',
];

public function bookings()
{
    return $this->hasMany(CarBooking::class);
}

}

