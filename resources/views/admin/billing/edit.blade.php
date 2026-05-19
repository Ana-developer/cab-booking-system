@extends('admin.layout')

@section('content')

<div class="container mt-4">

    <h4 class="mb-4">Open Bill</h4>

    {{-- Booking Summary --}}
<div class="card mb-4">
    <div class="card-body">

        @php
            $from = \Carbon\Carbon::parse($booking->start_date);
            $to = \Carbon\Carbon::parse($booking->end_date);
            $days = $from->diffInDays($to) + 1;
        @endphp

        <div class="row">

            <div class="col-md-4">
                <strong>Customer:</strong><br>
                {{ optional($booking->customer)->name ?? '-' }}
            </div>

            <div class="col-md-4">
                <strong>Car:</strong><br>
                {{ $booking->car->car_name ?? '-' }}
            </div>

            <div class="col-md-4">
                <strong>Booking Dates:</strong><br>

                {{ $from->format('d M Y') }}
                to
                {{ $to->format('d M Y') }}

                <br>

                <small class="text-muted">
                    {{ $days }} days
                </small>
            </div>

        </div>

    </div>
</div>

    {{-- Billing Form --}}
    <div class="card">
        <div class="card-body">

            <form method="POST" action="{{ route('billing.save', $booking->id) }}">
                @csrf

                <div class="row mb-3">

                    <div class="col-md-3">
                        <label>Base Amount</label>
                        <input type="number"
       name="base_amount"
       id="base_amount"
       class="form-control"
value="{{ old('base_amount', $billing->base_amount ?? $booking->amount ?? 0) }}" readonly >

                    </div>



                    <div class="col-md-3">
                        <label>Extra KM</label>
                        <input type="number"
                               name="extra_km"
                               id="extra_km"
                               class="form-control"
                               value="{{ $billing->extra_km ?? 0 }}">
                    </div>

                    <div class="col-md-3">
                        <label>Rate per KM</label>
                        <input type="number"
                               name="extra_km_rate"
                               id="extra_km_rate"
                               class="form-control"
                               value="{{ $billing->extra_km_rate ?? 0 }}">
                    </div>

                    <div class="col-md-3">
                        <label>Advance Paid</label>
                        <input type="number"
                               name="advance_paid"
                               id="advance_paid"
                               class="form-control"
                               value="{{ $billing->advance_paid ?? 0 }}">
                    </div>

                </div>

                <div class="row mb-3">

                    <div class="col-md-4">
                        <label>Total Amount</label>
                        <input type="text"
                               id="total_amount"
                               class="form-control bg-light"
                               readonly>
                    </div>

                    <div class="col-md-4">
                        <label>Balance Amount</label>
                        <input type="text"
                               id="balance_amount"
                               class="form-control bg-light"
                               readonly>
                    </div>

                    <div class="col-md-4">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="pending"
                                {{ ($billing->status ?? '') == 'pending' ? 'selected' : '' }}>
                                Pending
                            </option>
                            <option value="paid"
                                {{ ($billing->status ?? '') == 'paid' ? 'selected' : '' }}>
                                Paid
                            </option>s
                        </select>
                    </div>

                </div>

                <button type="submit" class="btn btn-dark">
                    Save Bill
                </button>

            </form>

        </div>
    </div>

</div>

@endsection

@section('scripts')

<script>

document.addEventListener("DOMContentLoaded", function () {

    function calculateBill() {

        let base = parseFloat(document.getElementById('base_amount').value) || 0;
        let extraKm = parseFloat(document.getElementById('extra_km').value) || 0;
        let rate = parseFloat(document.getElementById('extra_km_rate').value) || 0;
        let advance = parseFloat(document.getElementById('advance_paid').value) || 0;

        let extraCharge = extraKm * rate;
        let total = base + extraCharge;
        let balance = total - advance;

        document.getElementById('total_amount').value = total.toFixed(2);
        document.getElementById('balance_amount').value = balance.toFixed(2);
    }

    // Attach event listeners
    ['base_amount', 'extra_km', 'extra_km_rate', 'advance_paid']
        .forEach(function(id) {
            document.getElementById(id).addEventListener('input', calculateBill);
        });

    // Run on page load
    calculateBill();

});

</script>




@endsection

