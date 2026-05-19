<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\BookingBilling;
use App\Models\Customer;



class CarBooking extends Model
{
    protected $fillable = [
    'customer_id',
        'car_id',
        'start_date',
        'end_date',
        'booking_type',
          'amount', 
        'status',
    ];

 

public function billing()
{
    return $this->hasOne(Billing::class, 'booking_id');
}


protected $casts = [
    'start_date' => 'date',
    'end_date'   => 'date',
];



    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id');
    }

public function customer()
{
    return $this->belongsTo(Customer::class);
}

}
