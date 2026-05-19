@extends('admin.layout')

@section('content')
<div class="container mt-4">
    <h4>Edit Company</h4>

    <form method="POST" action="{{ route('admin.companies.update', $company->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Company Name</label>
            <input type="text" name="name" class="form-control"
                   value="{{ $company->name }}" required>
        </div>

        <div class="mb-3">
            <label>Owner Name</label>
            <input type="text" name="owner_name" class="form-control"
                   value="{{ $company->owner_name }}">
        </div>

        <div class="mb-3">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control"
                   value="{{ $company->phone }}">
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('admin.companies.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
