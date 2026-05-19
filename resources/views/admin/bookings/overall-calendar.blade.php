@extends('admin.layout')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
<style>
#calendar {
    min-height: 700px;
}


.fc-daygrid-day-frame {
    position: relative;
}

.add-booking-btn {
    position: absolute;
    top: 4px;
    left: 4px;               /* ⬅️ moved to left */
    width: 18px;
    height: 18px;
    line-height: 18px;
    text-align: center;
    font-size: 13px;
    font-weight: bold;
    border-radius: 50%;
    background: #e0e0e0;     /* light grey */
    color: #333;
    cursor: pointer;
    z-index: 5;
}

.fc-day-past {
    background-color: #f8f9fa;
    opacity: 0.6;
    cursor: not-allowed;
}


.add-booking-btn:hover {
    background: #cfcfcf;
}



</style>
@endpush


@section('content')
<div class="container-fluid">

    <h4 class="mb-3">Booking Calendar</h4>

<!-- Legend -->
<div class="mb-3">

    <button class="btn btn-dark btn-sm me-2"
            onclick="filterByCar('all')">
        All Cars (<span id="count-all">0</span>)
    </button>

    @foreach($cars as $car)
        <button class="btn btn-sm me-2"
                style="background-color: {{ $carColors[$car->id] }}; color:white"
                onclick="filterByCar({{ $car->id }})">
            {{ $car->car_name }}
            (<span id="count-car-{{ $car->id }}">0</span>)
        </button>
    @endforeach

</div>





    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif


    <div class="card">

   <div class="card-header d-flex justify-content-end">
    <a href="{{ route('billing.index') }}"
       class="btn btn-dark">
        Billing
    </a>
</div>

        <div class="card-body">
            <div id="calendar"></div>

            

        </div>

    </div>

</div>
 
<!-- Add Booking Modal -->
<div class="modal fade" id="addBookingModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.bookings.store') }}" method="POST">
            @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Add Booking</h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>
                </div>

                <div class="modal-body">

                    {{-- Car --}}
<div class="mb-3">
    <label>Select Car</label>

    <select name="car_id"
            id="car_id"
            class="form-select"
            required>

        <option value="">Select Car</option>

        @foreach($cars as $car)
            <option value="{{ $car->id }}">
                {{ $car->car_name }}
            </option>
        @endforeach

    </select>
</div>

{{-- Dates --}}
<div class="row">

    <div class="col-md-6 mb-3">
        <label>Start Date</label>

        <input type="date"
               name="start_date"
               id="start_date"
               class="form-control"
               required>
    </div>

    <div class="col-md-6 mb-3">
        <label>End Date</label>

        <input type="date"
               name="end_date"
               id="end_date"
               class="form-control"
               required>
    </div>

</div>

                    {{-- Customer Name --}}
                    <div class="mb-3">
                        <label>Customer Name</label>

                        <input type="text"
                               name="customer_name"
                               class="form-control"
                               required>
                    </div>

                    {{-- Phone --}}
                    <div class="mb-3">
                        <label>Phone Number</label>

                        <input type="text"
                               name="phone"
                               class="form-control"
                               required>
                    </div>

                    {{-- Email --}}
                    <div class="mb-3">
                        <label>Email</label>

                        <input type="email"
                               name="email"
                               class="form-control">
                    </div>

                    {{-- Booking Type --}}
                    <div class="mb-3">
                        <label>Booking Type</label>

                        <select name="booking_type"
                                class="form-select"
                                required>

                            <option value="normal">Normal</option>
                            <option value="wedding">Wedding</option>

                        </select>
                    </div>

                </div>

                <div class="modal-footer">

                    <button type="submit"
                            class="btn btn-primary">
                        Confirm Booking
                    </button>

                </div>

            </div>
        </form>
    </div>
</div>

<!-- Edit Booking Modal -->
<div class="modal fade" id="editBookingModal" tabindex="-1">
    <div class="modal-dialog">

        <form method="POST" id="editBookingForm">
            @csrf
            @method('PUT')

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Booking</h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>
                </div>

                <div class="modal-body">

                    {{-- Car --}}
                    <div class="mb-3">
                        <label>Select Car</label>

                        <select name="car_id"
                                id="edit_car_id"
                                class="form-select"
                                required>

                            @foreach($cars as $car)
                                <option value="{{ $car->id }}">
                                    {{ $car->car_name }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    {{-- Dates --}}
                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label>Start Date</label>

                            <input type="date"
                                   name="start_date"
                                   id="edit_start_date"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>End Date</label>

                            <input type="date"
                                   name="end_date"
                                   id="edit_end_date"
                                   class="form-control"
                                   required>
                        </div>

                    </div>

                    {{-- Customer --}}
                    <div class="mb-3">
                        <label>Customer Name</label>

                        <input type="text"
                               name="customer_name"
                               id="edit_customer_name"
                               class="form-control"
                               required>
                    </div>

                    <div class="mb-3">
                        <label>Phone</label>

                        <input type="text"
                               name="phone"
                               id="edit_phone"
                               class="form-control"
                               required>
                    </div>

                    <div class="mb-3">
                        <label>Email</label>

                        <input type="email"
                               name="email"
                               id="edit_email"
                               class="form-control">
                    </div>

                    {{-- Booking Type --}}
                    <div class="mb-3">
                        <label>Booking Type</label>

                        <select name="booking_type"
                                id="edit_booking_type"
                                class="form-select">

                            <option value="normal">Normal</option>
                            <option value="wedding">Wedding</option>

                        </select>
                    </div>

                    {{-- Amount --}}
                    <div class="mb-3">
                        <label>Amount</label>

                        <input type="text"
                               id="edit_amount"
                               class="form-control"
                               readonly>
                    </div>

                </div>

                <div class="modal-footer d-flex justify-content-between">

                    <button type="button"
                            class="btn btn-danger"
                            onclick="deleteBooking()">
                        Delete
                    </button>

                    <button class="btn btn-primary">
                        Update Booking
                    </button>

                </div>

            </div>

        </form>

    </div>
</div>


   

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
let allEvents = @json($events);
console.log(allEvents);

let calendar;

function updateLegendCounts() {

    // All bookings
    document.getElementById('count-all').innerText = allEvents.length;

    let carCounts = {};

    allEvents.forEach(event => {
        const carId = event.extendedProps.car_id;
        carCounts[carId] = (carCounts[carId] || 0) + 1;
    });

    Object.keys(carCounts).forEach(carId => {
        const el = document.getElementById(`count-car-${carId}`);
        if (el) {
            el.innerText = carCounts[carId];
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {

    const calendarEl = document.getElementById('calendar');

    calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    height: 700,
    editable: true,
    selectable: true,


    events: allEvents,

    dateClick(info) {
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        const clickedDate = new Date(info.date);
        clickedDate.setHours(0, 0, 0, 0);

        if (clickedDate < today) {
            Swal.fire({
                icon: 'info',
                title: 'Past date',
                text: 'You cannot add bookings for past dates'
            });
            return;
        }

        openBookingModal(info.dateStr);

    },

    eventClick(info) {
        openEditBookingModal(info.event);
    },

    dayCellDidMount(info) {
    const frame = info.el.querySelector('.fc-daygrid-day-frame');
    if (!frame) return;

    const today = new Date();
    today.setHours(0, 0, 0, 0);

    const cellDate = new Date(info.date);
    cellDate.setHours(0, 0, 0, 0);

    // 🚫 past date → no +
    if (cellDate < today) return;

    if (frame.querySelector('.add-booking-btn')) return;

    const btn = document.createElement('div');
    btn.className = 'add-booking-btn';
    btn.innerHTML = '+';

    btn.onclick = function (e) {
        e.preventDefault();
        e.stopPropagation();
       openBookingModal(info.dateStr);

    };

    frame.appendChild(btn);
},  // ✅ THIS COMMA FIXES EVERYTHING

selectAllow(info) {
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    return info.start >= today;
},

eventDrop: function(info) {
    handleBookingMove(info);
},


});



    calendar.render();
    updateLegendCounts();

});



function openBookingModal(date) {

    document.getElementById('start_date').value = date;
    document.getElementById('end_date').value = date;

    bootstrap.Modal
        .getOrCreateInstance(document.getElementById('addBookingModal'))
        .show();
}



let selectedBookingId = null;

function openEditBookingModal(event) {

    if (!event.id) {
        console.error('Booking ID missing');
        return;
    }

    selectedBookingId = event.id;

    document.getElementById('edit_car_id').value =
        event.extendedProps.car_id;

    document.getElementById('edit_booking_type').value =
        event.extendedProps.booking_type ?? 'normal';

    document.getElementById('edit_start_date').value =
        event.startStr;

    document.getElementById('edit_end_date').value =
        event.endStr
            ? event.endStr.substring(0, 10)
            : event.startStr;

    document.getElementById('edit_customer_name').value =
        event.extendedProps.customer_name ?? '';

    document.getElementById('edit_phone').value =
        event.extendedProps.phone ?? '';

    document.getElementById('edit_email').value =
        event.extendedProps.email ?? '';

    document.getElementById('edit_amount').value =
        event.extendedProps.amount
            ? '₹ ' + event.extendedProps.amount
            : 'N/A';

    document.getElementById('editBookingForm').action =
        `/admin/bookings/${selectedBookingId}`;

    new bootstrap.Modal(
        document.getElementById('editBookingModal')
    ).show();
}


function handleBookingMove(info) {

const bookingId = info.event.id;

const startDate = info.event.startStr;
const endDate = info.event.end
    ? info.event.end.toISOString().substring(0, 10)
    : startDate;

fetch(`/admin/bookings/${bookingId}/move`, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify({
        start_date: startDate,
        end_date: endDate
    })
})
.then(res => res.json())
.then(data => {

    if (!data.success) {
        info.revert(); // 🔥 revert drag
        Swal.fire({
            icon: 'error',
            title: 'Booking Conflict',
            text: data.message
        });
    } else {
        Swal.fire({
            icon: 'success',
            title: 'Updated',
            text: 'Booking dates updated',
            timer: 1500,
            showConfirmButton: false
        });
    }
})
.catch(() => {
    info.revert();
    Swal.fire('Error', 'Something went wrong', 'error');
});
}


function deleteBooking() {
    Swal.fire({
        title: 'Delete Booking?',
        text: 'This action cannot be undone',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Yes, delete'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/bookings/${selectedBookingId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(() => {
                location.reload();
            });
        }
    });
}

function filterByCar(carId) {

let filteredEvents;

if (carId === 'all') {
    filteredEvents = allEvents;
} else {
    filteredEvents = allEvents.filter(event =>
        event.extendedProps.car_id === carId
    );
}

calendar.removeAllEvents();
calendar.addEventSource(filteredEvents);
}


</script>

@if(session('swal_error'))
<script>
Swal.fire({
    icon: 'error',
    title: "{{ session('swal_error.title') }}",
    text: "{{ session('swal_error.text') }}",
    confirmButtonColor: '#d33'
});
</script>
@endif

@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Success',
    text: "{{ session('success') }}",
    timer: 2000,
    showConfirmButton: false
});
</script>
@endif

@endpush
