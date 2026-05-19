@extends('admin.layout')

@section('content')

<div class="card">
    <div class="card-header">
        <h5>Edit Car</h5>
    </div>

    <div class="card-body">

        <form action="{{ route('admin.cars.update', $car->id) }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <!-- Company -->
            <div class="mb-3">
                <label class="form-label">Company</label>
                <select name="company_id" class="form-select" required>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}"
                            {{ $car->company_id == $company->id ? 'selected' : '' }}>
                            {{ $company->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Car Name -->
            <div class="mb-3">
                <label class="form-label">Car Name</label>
                <input type="text"
                       name="car_name"
                       class="form-control"
                       value="{{ $car->car_name }}"
                       required>
            </div>

            <!-- Price -->
            <div class="mb-3">
                <label class="form-label">Price Per Day (₹)</label>
                <input type="number"
                       name="price_per_day"
                       class="form-control"
                       value="{{ $car->price_per_day }}"
                       required>
            </div>

            <div class="mb-3">
    <label>Wedding Price (₹)</label>
    <input type="number"
           name="wedding_price"
           class="form-control"
           value="{{ old('wedding_price', $car->wedding_price) }}"
           required>
</div>


            <!-- Current Image -->
            @if($car->image)
            <div class="mb-3">
                <label class="form-label">Current Image</label><br>
                <img src="{{ asset('storage/'.$car->image) }}"
                     width="150"
                     class="rounded shadow">
            </div>
            @endif

            <!-- New Image -->
            <div class="mb-3">
                <label class="form-label">Change Image</label>
                <input type="file"
                       name="image"
                       class="form-control"
                       accept="image/*">
            </div>

            <!-- Status -->
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="1" {{ $car->status ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ !$car->status ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.cars.index') }}"
                   class="btn btn-secondary">
                    Cancel
                </a>

                <button type="submit"
                        class="btn btn-success">
                    Update Car
                </button>
            </div>

        </form>

    </div>
</div>

@endsection
