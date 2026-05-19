@extends('admin.layout')

@section('content')

<style>
body {
    background: #f4f6f9;
}

.invoice-wrapper {
    width: 210mm;
    min-height: 297mm;
    margin: auto;
    background: white;
    padding: 40px;
    box-shadow: 0 0 25px rgba(0,0,0,0.1);
}

.invoice-header {
    background: #1e3a8a;
    color: white;
    padding: 25px;
    border-radius: 8px;
}

.invoice-header h1 {
    margin: 0;
    font-size: 30px;
    letter-spacing: 2px;
}

.invoice-details {
    margin-top: 10px;
    font-size: 14px;
}

.section-title {
    margin-top: 40px;
    font-weight: bold;
    font-size: 18px;
    border-bottom: 2px solid #ddd;
    padding-bottom: 5px;
}

.table-invoice td {
    padding: 10px 0;
}

.table-invoice tr td:last-child {
    text-align: right;
}

.total-section {
    border-top: 3px solid #000;
    font-size: 18px;
    font-weight: bold;
}

.footer-note {
    margin-top: 50px;
    text-align: center;
    font-size: 14px;
    color: #555;
}

.action-buttons {
    text-align: center;
    margin-top: 25px;
}

.action-buttons button {
    margin: 0 10px;
}

.btn-download {
    background-color: #1e3a8a;
    color: #fff;
    padding: 10px 22px;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    cursor: pointer;
}

.btn-share {
    background-color: #25D366;
    color: #fff;
    padding: 10px 22px;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    cursor: pointer;
}

.btn-download:hover,
.btn-share:hover {
    opacity: 0.9;
}

@media print {

    body * {
        visibility: hidden;
    }

    #invoiceArea, #invoiceArea * {
        visibility: visible;
    }

    #invoiceArea {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }

}

@media print {
    .no-print {
        display: none;
    }
}

@page {
    size: A4;
    margin: 20mm;
}



.paid-watermark {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) rotate(-25deg);
    font-size: 110px;
    font-weight: 800;
    letter-spacing: 15px;
    color: rgba(0, 128, 0, 0.10);
    border: 8px solid rgba(0, 128, 0, 0.10);
    padding: 20px 60px;
    border-radius: 20px;
    text-transform: uppercase;
    z-index: 10;
    pointer-events: none;
}

</style>
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<div class="container mt-4">
<div id="invoiceArea">

@if($billing->status == 'paid')

    <span class="badge bg-success">
        PAID
    </span>

@else

    <span class="badge bg-warning text-dark">
        PENDING
    </span>

@endif


<div id="invoice" class="invoice-wrapper">

    <!-- HEADER -->
    <div class="invoice-header d-flex justify-content-between align-items-center">
@if($billing->status == 'paid')
    <div class="paid-watermark">
        PAID
    </div>
@endif
    <div>
        <h1>INVOICE</h1>

        <div class="invoice-details">
            Invoice No: {{ $billing->invoice_number }} <br>
            Date: {{ $billing->created_at->format('d M Y') }}
        </div>
    </div>

    <div class="text-end">
        <img src="{{ asset('images/RWC-logo.png') }}" height="70">

        <div class="mt-2">
            Goa, India <br>
            Phone: +91 90216 35148
        </div>
</div>
</div>

<!-- CUSTOMER -->
<div class="section-title">Customer Details</div>

<p>
    <strong>Name:</strong>
    {{ optional($billing->booking->customer)->name ?? '-' }}
    <br>

    <strong>Phone:</strong>
    {{ optional($billing->booking->customer)->phone ?? '-' }}
    <br>

    <strong>Email:</strong>
    {{ optional($billing->booking->customer)->email ?? '-' }}
</p>

    <!-- TRIP -->
    <!-- <div class="section-title">Trip Information</div>

    <p>
        <strong>Pickup:</strong> {{ $billing->booking->pickup_location ?? '-' }} <br>
        <strong>Drop:</strong> {{ $billing->booking->drop_location ?? '-' }} <br> 
        <strong>KM Driven:</strong> {{ $billing->extra_km ?? 0 }} KM
    </p> -->

    <!-- BILLING -->
    <div class="section-title">Billing Summary</div>

    <table class="table table-invoice">
        <tr>
            <td>Base Amount</td>
            <td>₹ {{ number_format($billing->base_amount, 2) }}</td>
        </tr>
         <tr>
        <td>KM Driven</td>
      <td>
    ₹ {{ number_format(($billing->extra_km ?? 0) * ($billing->extra_km_rate ?? 0), 2) }}
</td>
    </tr>
        <tr>
            <td>Advance Paid</td>
            <td style="color:green;">₹ {{ number_format($billing->advance_paid, 2) }}</td>
        </tr>
        <tr>
    <td>
        {{ $billing->balance_amount < 0 ? 'Refund Amount' : 'Pending Amount' }}
    </td>

    <td style="color:{{ $billing->balance_amount < 0 ? 'green' : 'red' }};">
        ₹ {{ number_format(abs($billing->balance_amount), 2) }}
    </td>
</tr>

        <tr class="total-section">
            <td>Total Payable</td>
            <td>₹ {{ number_format($billing->total_amount, 2) }}</td>
        </tr>
    </table>

    @php
$upiId = "enslyrodrigues-1@okaxis";
$amount = $billing->balance_amount;
$upiLink = "upi://pay?pa={$upiId}&pn=CabService&am={$amount}&cu=INR";

$qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($upiLink);
@endphp

@if($billing->balance_amount > 0)
    <div style="text-align:center; margin-top:20px;">
        <img src="{{ $qrUrl }}" width="150">
        <p>Scan to Pay</p>
    </div>
@endif

    <div class="footer-note">
        Thank you for choosing our cab service.
    </div>

</div>

<div class="action-buttons no-print align-items-center">
    <button onclick="downloadInvoice()" class="btn-download">
        ⬇ Download PDF
    </button>

    <button onclick="shareInvoice()" class="btn-share">
    📤 Share Invoice
</button>
</div>
</div>
</div>
</div>
</div>


@endsection

<script>
function downloadInvoice() {
    window.print();
}

async function shareInvoice() {

    if (!navigator.canShare) {
        alert("Sharing not supported on this device.");
        return;
    }

    const invoice = document.querySelector("#invoiceArea");

    html2canvas(invoice, { scale: 2 }).then(async canvas => {

        canvas.toBlob(async function(blob) {

            const file = new File([blob], "Invoice.png", { type: "image/png" });

            try {
                await navigator.share({
                    title: "Cab Invoice",
                    text: "Here is your cab invoice.",
                    files: [file]
                });

            } catch (error) {
                console.log("Sharing failed", error);
            }

        }, "image/png");

    });
}
</script>