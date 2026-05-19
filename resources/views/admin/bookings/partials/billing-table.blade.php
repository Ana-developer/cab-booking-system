 <div class="card-body table-responsive">

            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Car</th>
                        <th>Booking Dates</th>
                        <th>Days</th>
                        <th>Total</th>
                        <th>Advance</th>
                        <th>Pending</th>
                        <th>Status</th>
                        <th>Actions</th>
                        
                    </tr>
                    

                </thead>

<tbody>
@foreach($bookings as $booking)

    @php
        $billing = $booking->billing;
        $total = $billing->total_amount ?? 0;
        $advance = $billing->advance_paid ?? 0;
        $pending = $billing ? $billing->balance_amount : 0;
        $status = $billing->status ?? 'pending';

    $from = \Carbon\Carbon::parse($booking->start_date);
    $to = \Carbon\Carbon::parse($booking->end_date);
    $days = $from->diffInDays($to) + 1;

@endphp


    <tr>
        {{-- Serial Number --}}
        <td>{{ $loop->iteration + ($bookings->firstItem() - 1) }}</td>

<td>{{ $booking->customer->name ?? '-' }}</td>

        <td>{{ $booking->car->car_name ?? '-' }}</td>

        {{-- Booking Dates in One Column --}}
       <td>
    {{ $from->format('d M Y') }}
    <br>
    <small class="text-muted">
        to {{ $to->format('d M Y') }}
    </small>
</td>

@php
    $days = $from->diffInDays($to) + 1;
@endphp

<td>{{ $days }} days</td>

        <td>₹ {{ $total }}</td>
        <td>₹ {{ $advance }}</td>
        <td>₹ {{ $pending }}</td>

        {{-- Status --}}
      <td>

@if(!$booking->billing)

    <a href="{{ route('billing.edit', $booking->id) }}" 
       class="btn btn-sm btn-primary">
        Open Bill
    </a>

@elseif($booking->billing->status == 'pending')

    <span class="badge bg-warning text-dark">Pending</span>

    <button 
        class="btn btn-sm btn-success mt-1 markPaidBtn"
        data-id="{{ $booking->billing->id }}">
        Mark as Paid
    </button>

@elseif($booking->billing->status == 'paid')

    <span class="badge bg-success">Paid</span>

@endif

</td>

<td>

@if($booking->billing)

    {{-- Edit Bill --}}
    <a href="{{ route('billing.edit', $booking->id) }}"
       class="btn btn-sm btn-warning mb-1">
        Edit
    </a>

    <br>

    {{-- Invoice --}}
    <a href="{{ route('billing.invoice', $booking->billing->id) }}" 
       class="btn btn-sm btn-info">
        Invoice
    </a>

@else
    -
@endif

</td>


    </tr>

@endforeach
</tbody>

            </table>

<div class="d-flex justify-content-between align-items-center mt-3">
    <div>
        Showing {{ $bookings->firstItem() }}
        to {{ $bookings->lastItem() }}
        of {{ $bookings->total() }} results
    </div>

    <div>
        {{ $bookings->links() }}
    </div>
</div>