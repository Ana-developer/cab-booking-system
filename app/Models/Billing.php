<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    protected $fillable = [
        'booking_id',
        'base_amount',
        'extra_km',
        'extra_km_rate',
        'advance_paid',
        'total_amount',
        'balance_amount',
        'status'
    ];


    
    public function booking()
    {
        return $this->belongsTo(CarBooking::class, 'booking_id');
    }


    protected static function boot()
{
    parent::boot();

    static::creating(function ($billing) {

        $lastInvoice = Billing::orderBy('id', 'desc')->first();

        if ($lastInvoice && $lastInvoice->invoice_number) {
            $lastNumber = (int) substr($lastInvoice->invoice_number, -5);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $billing->invoice_number = 'INV-' . date('Y') . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    });
}
}
