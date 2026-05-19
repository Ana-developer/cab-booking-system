@extends('admin.layout')

@section('content')

<h4 class="mb-3">
    Availability Calendar – {{ $car->car_name }}
</h4>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif


<div id="calendar"></div>

<!-- Booking Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('admin.car.bookings.store') }}" class="modal-content">
            @csrf

            <input type="hidden" name="car_id" value="{{ $car->id }}">

            <div class="modal-header">
                <h5 class="modal-title">Add Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Booking Type</label>
                    <select name="booking_type" class="form-select">
                        <option value="normal">Normal</option>
                        <option value="wedding">Wedding</option>
                    </select>
                </div>

            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-success">
                    Save Booking
                </button>
            </div>

        </form>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const calendar = new FullCalendar.Calendar(
        document.getElementById('calendar'), {
            initialView: 'dayGridMonth',
            selectable: true,
            selectMirror: true,
            height: 650,
            eventColor: '#dc3545',

            select: function (info) {
                document.getElementById('start_date').value = info.startStr;
                document.getElementById('end_date').value =
                    info.endStr ? info.endStr.split('T')[0] : info.startStr;

                const modal = new bootstrap.Modal(
                    document.getElementById('bookingModal')
                );
                modal.show();
            }
        }
    );

    calendar.render();
});
</script>


@endsection
