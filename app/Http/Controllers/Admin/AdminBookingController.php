<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarBooking;
use App\Models\Car;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    public function calendar()
    {
        $cars = Car::all();
    
        $colors = [
            '#e74c3c', '#3498db', '#2ecc71',
            '#9b59b6', '#f39c12', '#1abc9c'
        ];
        $carColors = [];
        foreach ($cars as $index => $car) {
            $carColors[$car->id] = $colors[$index % count($colors)];
        }
    
$events = CarBooking::with(['car', 'customer'])
    ->get()
    ->map(function ($booking) use ($carColors) {

        $baseColor = $carColors[$booking->car_id];

$isWedding = $booking->booking_type === 'wedding';

$borderColor = $isWedding
    ? '#001eff'
    : $baseColor;

return [
    'id' => $booking->id,

    'title' => $booking->car->car_name . ' - ' .
               ($booking->customer->name ?? 'Customer'),

    'start' => $booking->start_date->format('Y-m-d'),

    'end' => $booking->end_date
                ->copy()
                ->addDay()
                ->format('Y-m-d'),

    'backgroundColor' => $baseColor,
    'borderColor' => $borderColor,
    'borderWidth' => $isWedding ? 5 : 1,
    'textColor' => '#ffffff',

    'extendedProps' => [
        'car_id' => $booking->car_id,
        'booking_type' => $booking->booking_type,
        'amount' => $booking->amount,

        'customer_name' => optional($booking->customer)->name,
        'phone' => optional($booking->customer)->phone,
        'email' => optional($booking->customer)->email,
    ]
];
            
    });
 return view('admin.bookings.overall-calendar', [
    'events' => $events,
    'cars' => $cars,
    'carColors' => $carColors
]);
    }
    

public function store(Request $request)
{
    $exists = CarBooking::where('car_id', $request->car_id)
    ->where(function ($q) use ($request) {
        $q->whereBetween('start_date', [$request->start_date, $request->end_date])
          ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
          ->orWhere(function ($q2) use ($request) {
              $q2->where('start_date', '<=', $request->start_date)
                 ->where('end_date', '>=', $request->end_date);
          });
    })
    ->exists();

if ($exists) {
   return redirect()->back()->with('swal_error', [
    'title' => 'Booking Conflict',
    'text'  => 'This car is already booked for the selected date'
]);

}

    $request->validate([
        'car_id' => 'required|exists:cars,id',
        'booking_type' => 'required|in:normal,wedding',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
    ]);

    $car = Car::findOrFail($request->car_id);

    // Calculate days (minimum 1 day)
    $start = \Carbon\Carbon::parse($request->start_date);
    $end = \Carbon\Carbon::parse($request->end_date);
    $days = $start->diffInDays($end) + 1;

if ($request->booking_type === 'wedding') {
    $amount = $car->wedding_price;
} else {
    $amount = $car->price_per_day * $days;
}


    CarBooking::create([
        'car_id' => $request->car_id,
        'booking_type' => $request->booking_type,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'amount' => $amount,
        'status' => 'booked',
    ]);

    return redirect()->back()->with('success', 'Booking added successfully');
}


public function update(Request $request, CarBooking $booking)
{
    $request->validate([
        'booking_type' => 'required|in:normal,wedding',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
    ]);

    $car = $booking->car;

    $start = Carbon::parse($request->start_date);
    $end = Carbon::parse($request->end_date);
    $days = $start->diffInDays($end) + 1;

    if ($request->booking_type === 'wedding') {
        $amount = $car->wedding_price;
    } else {
        $amount = $car->price_per_day * $days;
    }

    $booking->update([
        'booking_type' => $request->booking_type,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'amount' => $amount,
    ]);

    return redirect()->back()->with('success', 'Booking updated');
}


public function destroy(CarBooking $booking)
{
    $booking->delete();

    return response()->json(['status' => 'deleted']);
}

public function move(Request $request, CarBooking $booking)
{
    $request->validate([
        'start_date' => 'required|date',
        'end_date'   => 'required|date|after_or_equal:start_date',
    ]);

    // 🔒 Prevent overlap with same car (exclude current booking)
    $overlap = CarBooking::where('car_id', $booking->car_id)
        ->where('id', '!=', $booking->id)
        ->where(function ($query) use ($request) {
            $query->where('start_date', '<=', $request->end_date)
                  ->where('end_date', '>=', $request->start_date);
        })
        ->exists();

    if ($overlap) {
        return response()->json([
            'success' => false,
            'message' => '🚫 Car already booked on selected date(s)'
        ]);
    }

    $booking->update([
        'start_date' => $request->start_date,
        'end_date'   => $request->end_date,
    ]);

    return response()->json(['success' => true]);
}


}
