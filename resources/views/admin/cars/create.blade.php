@extends('admin.layout')

@section('content')

<div class="card">
    <div class="card-header">
        <h5>Add New Car</h5>
    </div>

    <div class="card-body">

    <form action="{{ route('admin.cars.store') }}" method="POST"  enctype="multipart/form-data">
    @csrf

    <!-- Company -->
    <div class="mb-3">
        <label class="form-label">Company</label>
        <select name="company_id" class="form-select" required>
            <option value="">-- Select Company --</option>
            @foreach($companies as $company)
                <option value="{{ $company->id }}">{{ $company->name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Car Name -->
    <div class="mb-3">
        <label class="form-label">Car Name</label>
        <input type="text" name="car_name" class="form-control" required>
    </div>

        <!-- Image Upload -->
    <div class="mb-3">
    <label class="form-label">Upload Image</label>
    <input type="file" name="image" class="form-control">
    </div>

    <!-- Brand -->
    <div class="mb-3">
        <label class="form-label">Brand</label>
        <input type="text" name="brand" class="form-control" required>
    </div>

    <!-- Model -->
    <div class="mb-3">
        <label class="form-label">Model</label>
        <input type="text" name="model" class="form-control">
    </div>

    <!-- Car Type -->
    <div class="mb-3">
        <label class="form-label">Car Type</label>
        <select name="car_type" class="form-select" required>
            <option value="Sedan">Sedan</option>
            <option value="SUV">SUV</option>
            <option value="Luxury">Luxury</option>
        </select>
    </div>

    <!-- Fuel Type -->
    <div class="mb-3">
        <label class="form-label">Fuel Type</label>
        <select name="fuel_type" class="form-select" required>
            <option value="Petrol">Petrol</option>
            <option value="Diesel">Diesel</option>
            <option value="Electric">Electric</option>
        </select>
    </div>

    <!-- Price per day -->
    <div class="mb-3">
        <label class="form-label">Price Per Day (₹)</label>
        <input type="number" name="price_per_day" class="form-control" required>
    </div>

    <!-- Wedding Price -->
    <div class="mb-3">
        <label class="form-label">Wedding Price (₹)</label>
        <input type="number" name="wedding_price" class="form-control">
    </div>

    <!-- Car Number -->
    <div class="mb-3">
        <label class="form-label">Car Number</label>
        <input type="text" name="car_number" class="form-control" required>
    </div>

    <!-- Status -->
    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            <option value="1">Active</option>
            <option value="0">Inactive</option>
        </select>
    </div>

    <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('admin.cars.index') }}" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-success">Save Car</button>
    </div>
</form>


    </div>
</div>

@endsection
