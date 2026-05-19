<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CarBooking;
use App\Models\Billing;


class BillingController extends Controller
{
   
    public function edit($bookingId)
{
    $booking = CarBooking::with('car')->findOrFail($bookingId);

    $billing = Billing::where('booking_id', $bookingId)->first();

return view('admin.billing.edit', compact('booking', 'billing'));
}


  public function save(Request $request, CarBooking $booking)
{
    $base = $request->base_amount ?? 0;
    $extraKm = $request->extra_km ?? 0;
    $rate = $request->extra_km_rate ?? 0;
    $advance = $request->advance_paid ?? 0;

    $extraCharge = $extraKm * $rate;
    $total = $base + $extraCharge;
    $balance = $total - $advance;

    // Check if billing already exists
    $existingBilling = Billing::where('booking_id', $booking->id)->first();

    if ($existingBilling && $existingBilling->invoice_number) {
        $invoiceNumber = $existingBilling->invoice_number;
    } else {

        $lastInvoice = Billing::orderBy('id', 'desc')->first();

        if ($lastInvoice && $lastInvoice->invoice_number) {
            $lastNumber = (int) substr($lastInvoice->invoice_number, -5);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $invoiceNumber = 'INV-' . date('Y') . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    Billing::updateOrCreate(
        ['booking_id' => $booking->id],
        [
            'invoice_number' => $invoiceNumber,
            'base_amount' => $base,
            'extra_km' => $extraKm,
            'extra_km_rate' => $rate,
            'advance_paid' => $advance,
            'total_amount' => $total,
            'balance_amount' => $balance,
            'status' => $balance <= 0 ? 'paid' : 'pending'
        ]
    );

    return redirect()->route('billing.index')
        ->with('success', 'Bill saved successfully');
}

public function markPaid(Billing $billing)
{
    $billing->update([
        'status' => 'paid',
        'balance_amount' => 0
    ]);

    return redirect()->route('billing.index')
            ->with('success', 'Bill marked as paid successfully!');
}


public function index(Request $request)
{
    $search = $request->search;

    // default 10
    $perPage = $request->per_page ?? 10;

    // allow only safe values
    if (!in_array($perPage, [10, 50, 100])) {
        $perPage = 10;
    }

    $bookings = CarBooking::with(['billing', 'car', 'customer'])
    ->when($search, function ($query) use ($search) {

        $query->where('id', $search)

            ->orWhereHas('customer', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })

            ->orWhereHas('car', function ($q) use ($search) {
                $q->where('car_name', 'like', "%{$search}%");
            });

    })
    ->latest()
    ->paginate($perPage)
    ->withQueryString();

    return view('admin.billing.index', compact('bookings', 'search', 'perPage'));
}

public function billingList(Request $request)
{

$query = CarBooking::with(['billing', 'car', 'customer']);

if ($request->search) {

    $search = $request->search;

    $query->where(function ($q) use ($search) {

        $q->where('id', $search)

          ->orWhereHas('customer', function ($customerQuery) use ($search) {
              $customerQuery->where('name', 'like', '%' . $search . '%');
          })

          ->orWhereHas('car', function ($carQuery) use ($search) {
              $carQuery->where('car_name', 'like', '%' . $search . '%');
          });

    });
}
   

    $perPage = $request->per_page ?? 10;

    $bookings = $query->orderBy('id', 'desc')
                  ->paginate($perPage)
                  ->withQueryString();

    if ($request->ajax()) {
        return view('admin.bookings.partials.billing-table', compact('bookings'))->render();
    }

    return view('admin.bookings.billing-list', compact('bookings'));
}

public function store(Request $request)
{
    $booking = CarBooking::create([
        'customer_name' => $request->customer_name,
        'car_id' => $request->car_id,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'amount' => $request->base_amount, // 👈 important
    ]);

    return redirect()->route('billing.edit', $booking->id);
}

 public function invoice($id)
{
    $billing = Billing::with('booking.car')->findOrFail($id);

    return view('admin.billing.invoice', compact('billing'));
}

public function show($id)
{
    $booking = CarBooking::with(['billing', 'car', 'customer'])
                ->whereHas('billing', function ($q) use ($id) {
                    $q->where('id', $id);
                })
                ->firstOrFail();

    return view('admin.billing.show', compact('booking'));
}



}

