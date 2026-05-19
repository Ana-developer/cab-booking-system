<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\CarBooking;
use App\Models\Customer;

class CarController extends Controller
{
    
    public function index(Request $request)
    {
        $cars = Car::with('company')
            ->when($request->search, function ($q) use ($request) {
                $q->where('car_name', 'like', '%' . $request->search . '%');
            })
            ->latest()
            ->paginate(5);

        return view('admin.cars.index', compact('cars'));
    }

    public function create()
    {
        $companies = Company::orderBy('name')->get();
        return view('admin.cars.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_id'     => 'required|exists:companies,id',
            'car_name'       => 'required|string|max:255',
            'brand'          => 'required|string|max:255',
            'model'          => 'nullable|string|max:255',
            'car_type'       => 'required|string|max:255',
            'fuel_type'      => 'required|string|max:255',
            'price_per_day'  => 'required|numeric',
            'wedding_price'  => 'nullable|numeric',
            'car_number'     => 'required|string|max:255',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status'         => 'required|boolean',
        ]);
    
              // Image upload
    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('cars', 'public');
    }

        Car::create([
            'company_id'     => $request->company_id,
            'car_name'       => $request->car_name,
            'brand'          => $request->brand,
            'model'          => $request->model,
            'car_type'       => $request->car_type,
            'fuel_type'      => $request->fuel_type,
            'price_per_day'  => $request->price_per_day,
            'wedding_price'  => $request->wedding_price,
            'car_number'     => $request->car_number,
            'image'         => $imagePath,
            'status'         => $request->status,
        ]);
    
   
        return redirect()
        ->route('admin.cars.index')
        ->with('success', 'Car added successfully');
}
  

public function edit($id)
{
    $car = Car::findOrFail($id);
    $companies = Company::orderBy('name')->get();

    return view('admin.cars.edit', compact('car', 'companies'));
}


    public function destroy($id)
    {
        Car::findOrFail($id)->delete();

        return redirect()
            ->route('admin.cars.index')
            ->with('success', 'Car deleted successfully');
    }

    public function update(Request $request, $id)
{
    $car = Car::findOrFail($id);

    // Validation
    $request->validate([
        'company_id'     => 'required|exists:companies,id',
        'car_name'       => 'required|string|max:255',
        'price_per_day'  => 'required|numeric',
        'wedding_price' =>  'required|numeric',
        'image'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'status'         => 'required|boolean',
    ]);

    // Image upload
    if ($request->hasFile('image')) {

        // delete old image
        if ($car->image) {
            Storage::disk('public')->delete($car->image);
        }

        $imagePath = $request->file('image')->store('cars', 'public');
        $car->image = $imagePath;
    }

    // Update fields
    $car->update([
        'company_id'    => $request->company_id,
        'car_name'      => $request->car_name,
        'price_per_day' => $request->price_per_day,
        'wedding_price' => $request->wedding_price,
        'status'        => $request->status,

    ]);

    return redirect()
        ->route('admin.cars.index')
        ->with('success', 'Car updated successfully');
}


public function calendar(Car $car)
{
    $bookings = $car->bookings->map(function ($booking) {
        return [
            'title' => strtoupper($booking->booking_type),
            'start' => $booking->start_date,
            'end'   => date('Y-m-d', strtotime($booking->end_date . ' +1 day')),
        ];
    });

    return view('admin.cars.calendar', compact('car', 'bookings'));
}
   

public function storeBooking(Request $request)
{
    $request->validate([
        'customer_name' => 'required|string|max:150',
        'phone'         => 'required|string|max:20',

        'car_id'        => 'required|exists:cars,id',
        'start_date'    => 'required|date',
        'end_date'      => 'required|date|after_or_equal:start_date',
        'booking_type'  => 'required|in:normal,wedding',
    ]);

    // Prevent overlapping bookings
$exists = CarBooking::where('car_id', $request->car_id)

    ->where(function ($query) use ($request) {

        $query->whereBetween('start_date', [
                    $request->start_date,
                    $request->end_date
                ])

              ->orWhereBetween('end_date', [
                    $request->start_date,
                    $request->end_date
                ])

              ->orWhere(function ($q) use ($request) {

                    $q->where('start_date', '<=', $request->start_date)
                      ->where('end_date', '>=', $request->end_date);

              });

    })

    ->exists();
    
    if ($exists) {
        return back()->with('error', 'This car is already booked for selected dates.');
    }

    // Create or find customer
    $customer = Customer::firstOrCreate(
        ['phone' => $request->phone],
        [
            'name'  => $request->customer_name,
            'email' => $request->email
        ]
    );

    // Calculate amount
    $car = Car::findOrFail($request->car_id);

    $days = \Carbon\Carbon::parse($request->start_date)
        ->diffInDays($request->end_date) + 1;

    $amount = $request->booking_type == 'wedding'
        ? $car->wedding_price
        : ($car->price_per_day * $days);

    // Create booking
    CarBooking::create([
        'customer_id'  => $customer->id,
        'car_id'       => $request->car_id,
        'start_date'   => $request->start_date,
        'end_date'     => $request->end_date,
        'booking_type' => $request->booking_type,
        'amount'       => $amount,
    ]);

    return back()->with('success', 'Booking added successfully.');
}

public function overallCalendar()
{

  dd(
        CarBooking::with(['car', 'customer'])->first()->toArray()
    );

    $cars = Car::all();

    // Assign unique colors to cars
    $colors = [
        '#e74c3c', '#3498db', '#2ecc71',
        '#9b59b6', '#f39c12', '#1abc9c',
        '#e84393', '#6c5ce7'
    ];

    $carColors = [];
    foreach ($cars as $index => $car) {
        $carColors[$car->id] = $colors[$index % count($colors)];
    }

$bookings = CarBooking::with(['car', 'customer'])
    ->get()
    ->map(function ($booking) use ($carColors) {

        return [
            'id' => $booking->id,

            'title' => $booking->car->car_name . ' - ' .
                       ($booking->customer->name ?? 'Customer'),

            'start' => $booking->start_date->format('Y-m-d'),

            'end' => $booking->end_date
                        ->copy()
                        ->addDay()
                        ->format('Y-m-d'),

            'backgroundColor' => $carColors[$booking->car_id],
            'borderColor' => $carColors[$booking->car_id],

            // IMPORTANT:
            'extendedProps' => [
                'car_id' => $booking->car_id,
                'booking_type' => $booking->booking_type,
                'amount' => $booking->amount,

                'customer_name' => optional($booking->customer)->name,
                'phone' => optional($booking->customer)->phone,
                'email' => optional($booking->customer)->email,
            ],
        ];
    })
    ->values();

 return view('admin.bookings.overall-calendar', [
    'events' => $bookings,
    'carColors' => $carColors,
    'cars' => $cars
]);
}



}

