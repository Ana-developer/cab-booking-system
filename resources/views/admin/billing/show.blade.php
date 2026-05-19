@extends('admin.layout')

@section('content')

<h4>Billing – {{ $booking->car->car_name ?? 'N/A' }}</h4>

<p>
    Type: <strong>{{ ucfirst($booking->booking_type) }}</strong>
</p>

<p>
    Dates:
    {{ $booking->start_date->format('d-m-Y') }}
    →
    {{ $booking->end_date->format('d-m-Y') }}
</p>

<p>
    Days:
    {{ $booking->start_date->diffInDays($booking->end_date) + 1 }}
</p>



<form method="POST"
      action="{{ route('billing.save', $booking->id) }}"
      onkeydown="return event.key != 'Enter';">
    @csrf

    <div class="row">

        {{-- Base Amount --}}
        <div class="col-md-4">
            <label class="form-label">Base Amount</label>
            <input type="number"
                   class="form-control"
                   value="{{ $baseAmount }}"
                   readonly>
            <input type="hidden" name="base_amount" id="base_amount" value="{{ $baseAmount }}">
        </div>

        {{-- Extra KM --}}
        <div class="col-md-4">
                        <label>Extra KM</label>
         <input type="number"
       name="extra_km"
       id="extra_km"
       class="form-control"
       value="{{ $billing->extra_km ?? 0 }}"
       {{ optional($booking->billing)->status === 'paid' ? 'readonly' : '' }}>
        </div>

        {{-- Extra KM Rate --}}
        <div class="col-md-4">
            <label>Extra KM Rate (₹ / KM)</label>
            <input type="number"
                   name="extra_km_rate"
                   id="extra_km_rate"
                  value="{{ $billing->extra_km_rate ?? 0 }}"
                   class="form-control">
        </div>

        {{-- Advance Paid --}}
        <div class="col-md-4 mt-3">
            <label>Advance Paid</label>
            <input type="number"
                   name="advance_paid"
                   id="advance_paid"
                  value="{{ $billing->advance_paid ?? 0 }}"
                   class="form-control">
        </div>

        {{-- Total Amount --}}
        <div class="col-md-4 mt-3">
            <label>Total Amount</label>
            <input type="number"
                   id="total_amount"
                   class="form-control"
                   readonly>
        </div>

        {{-- Balance --}}
        <div class="col-md-4 mt-3">
            <label id="balance_label">Balance Amount</label>
            <input type="number"
                   id="balance_amount"
                   class="form-control"
                   readonly>
        </div>

    </div>

  @if(optional($booking->billing)->status !== 'paid')
    <button type="submit" class="btn btn-dark mt-3">
        Save Billing
    </button>

<div class="col-md-4">
    <label>Payment Status</label>
    <select name="status"
            class="form-control"
            {{ ($booking->billing->status ?? 'pending') === 'paid' ? 'disabled' : '' }}>
        <option value="pending"
            {{ ($booking->billing->status ?? '') === 'pending' ? 'selected' : '' }}>
            Pending
        </option>

        <option value="paid"
            {{ ($booking->billing->status ?? '') === 'paid' ? 'selected' : '' }}>
            Paid
        </option>

        <option value="refunded"
            {{ ($booking->billing->status ?? '') === 'refunded' ? 'selected' : '' }}>
            Refunded
        </option>
    </select>

    {{-- Send value even if disabled --}}
    @if(($booking->billing->status ?? '') === 'paid')
        <input type="hidden" name="status" value="paid">
    @endif
</div>


@else
    <div class="alert alert-success mt-3">
        🔒 Payment Completed — Billing Locked
    </div>
@endif

</form>

{{-- JS --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const baseAmount = parseFloat(document.getElementById('base_amount').value);

    const extraKmInput = document.getElementById('extra_km');
    const rateInput = document.getElementById('extra_km_rate');
    const advanceInput = document.getElementById('advance_paid');

    const totalInput = document.getElementById('total_amount');
    const balanceInput = document.getElementById('balance_amount');
    const balanceLabel = document.getElementById('balance_label');

    function calculateBilling() {
        const extraKm = parseFloat(extraKmInput.value) || 0;
        const rate = parseFloat(rateInput.value) || 0;
        const advance = parseFloat(advanceInput.value) || 0;

        const extraCharge = extraKm * rate;
        const total = baseAmount + extraCharge;
        const balance = total - advance;

        totalInput.value = total.toFixed(2);

        if (balance < 0) {
            balanceInput.value = Math.abs(balance).toFixed(2);
            balanceLabel.innerText = 'Refund Amount';
        } else {
            balanceInput.value = balance.toFixed(2);
            balanceLabel.innerText = 'Balance Amount';
        }
    }

    extraKmInput.addEventListener('input', calculateBilling);
    rateInput.addEventListener('input', calculateBilling);
    advanceInput.addEventListener('input', calculateBilling);

    calculateBilling(); // initial run
});
</script>

@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Saved!',
    text: '{{ session('success') }}',
    timer: 2000,
    showConfirmButton: false
});
</script>
@endif


@endsection
