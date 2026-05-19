@extends('admin.layout')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Cars</h4>
    <a href="{{ route('admin.cars.create') }}" class="btn btn-primary">
        + Add Car
    </a>
</div>

{{-- Success Message --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Search --}}
<form method="GET" class="mb-3 d-flex gap-2">
    <input type="text"
           name="search"
           class="form-control"
           placeholder="Search car"
           value="{{ request('search') }}">
    <button class="btn btn-dark">Search</button>
</form>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Company</th>
                    <th>Car Name</th>
                    <th>Image</th>
                    <th>Type</th>
                    <th>Fuel</th>
                    <th>Price / Day</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cars as $car)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $car->company->name ?? '-' }}</td>
                    <td class="fw-semibold">{{ $car->car_name }}</td>
                    <td>
    @if($car->image)
        <img src="{{ asset('storage/' . $car->image) }}"
             width="70"
             height="45"
             style="object-fit:cover;border-radius:6px;">
    @else
        <span class="text-muted">No Image</span>
    @endif
</td>
                    <td>{{ ucfirst($car->car_type) }}</td>
                    <td>{{ ucfirst($car->fuel_type) }}</td>
                    <td>₹ {{ $car->price_per_day }}</td>
                    <td class="text-center">
                    <td class="d-flex gap-2">

<!-- Edit -->
<a href="{{ route('admin.cars.edit', $car->id) }}"
   class="btn btn-sm btn-outline-primary"
   title="Edit">
    <i class="bi bi-pencil-square"></i>
</a>

<!-- Delete -->
<button type="button"
        class="btn btn-sm btn-outline-danger"
        data-bs-toggle="modal"
        data-bs-target="#deleteCarModal"
        data-id="{{ $car->id }}"
        data-name="{{ $car->car_name }}"
        title="Delete">
    <i class="bi bi-trash"></i>
</button>

<a href="{{ route('admin.cars.calendar', $car->id) }}"
   class="btn btn-sm btn-outline-dark"
   title="View Calendar">
   <i class="bi bi-calendar-event"></i>
</a>

</td>




                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        No cars found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>


    </div>
</div>

{{-- Pagination --}}
<div class="mt-3">
    {{ $cars->links() }}
</div>

        <!-- Delete Car Modal -->
        <div class="modal fade" id="deleteCarModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title text-danger">
                    <i class="bi bi-exclamation-triangle"></i> Confirm Delete
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                Are you sure you want to delete  
                <strong id="carName"></strong>?
                <br>
                <small class="text-muted">This action cannot be undone.</small>
            </div>

            <div class="modal-footer">

                <form id="deleteCarForm" method="POST">
                    @csrf
                    @method('DELETE')

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit"
                            class="btn btn-danger">
                        Yes, Delete
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const deleteModal = document.getElementById('deleteCarModal');

    deleteModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;

        const carId = button.getAttribute('data-id');
        const carName = button.getAttribute('data-name');

        document.getElementById('carName').textContent = carName;
        document.getElementById('deleteCarForm').action =
            `/admin/cars/${carId}`;
    });

});
</script>


@endsection
