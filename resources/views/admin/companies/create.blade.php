@extends('admin.layout')

@section('content')
<div class="container-fluid py-4">

    <div class="container">

        <!-- Page Header -->
        <div class="mb-4">
            <h1 class="page-title">Add Company</h1>
           
        </div>

        <!-- Card -->
        <div class="card admin-card">
            <div class="card-body p-4 p-md-5">

                <form method="POST" action="{{ route('admin.companies.store') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Company Info -->
                    <div class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Company Name</label>
                                <input type="text" name="name" class="form-control"
                                    placeholder="" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Owner Name</label>
                                <input type="text" name="owner_name" class="form-control"
                                    placeholder="">
                            </div>
                        </div>
                    </div>

                    <!-- Contact -->
                    <div class="mb-4">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control"
                                    placeholder="">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control"
                                    placeholder="">
                            </div>
                        </div>
                    </div>

                    <!-- Business Type -->
                    <div class="mb-4">
                        <label class="form-label">Business Type</label>
                        <select name="business_type" class="form-select">
                            <option value="normal">Normal Cab Service</option>
                            <option value="wedding">Wedding Cars</option>
                            <option value="both">Both</option>
                        </select>
                    </div>

                    <div class="mb-4">
    <label class="form-label">Company Logo</label>
    <input type="file" name="logo" class="form-control">
    <small class="text-muted">
        PNG / JPG | Max 2MB
    </small>
</div>

                    <!-- Actions -->
                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-end pt-3 border-top">
                        <a href="{{ route('admin.companies.create') }}"
                           class="btn btn-outline-secondary">
                            Cancel
                        </a>

                        <button type="submit" class="btn btn-primary">
                            Save Company
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>
@endsection
