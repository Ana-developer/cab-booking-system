@extends('admin.layout')

@section('content')

    <div class="page-title">
        Companies
        <a href="{{ route('admin.companies.create') }}" class="btn btn-primary">
    + Add Company
</a>
       
    </div>

   
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<form method="GET" class="mb-3 d-flex gap-2">
    <input type="text"
           name="search"
           class="form-control"
           placeholder="Search company"
           value="{{ request('search') }}">
    <button class="btn btn-dark">Search</button>
</form>


    <div class="card">
        <table width="100%" cellpadding="12" cellspacing="0">
            <thead style="background:#f8fafc;">
                <tr>
                    <th align="left">#</th>
                    <th align="left">Company Name</th>
                    <th align="left">Phone</th>
                    <th align="left">Owner Name</th>
                    <th align="left">Action</th>
                </tr>
            </thead>
            <tbody>
            @foreach($companies as $company)
<tr>
    <td>{{ $loop->iteration }}</td>
    <td>{{ $company->name }}</td>
    <td>{{ $company->phone }}</td>
    <td>{{ $company->owner_name }}</td>
    <td class="d-flex gap-2">
    <a href="{{ route('admin.companies.edit', $company->id) }}"
   class="btn btn-sm btn-outline-primary me-1"
   title="Edit">
    <i class="bi bi-pencil-square"></i>
</a>

<form action="{{ route('admin.companies.destroy', $company->id) }}"
      method="POST"
      class="d-inline"
      onsubmit="return confirm('Are you sure you want to delete this company?')">
    @csrf
    @method('DELETE')
    <button type="submit"
            class="btn btn-sm btn-outline-danger"
            title="Delete">
        <i class="bi bi-trash"></i>
    </button>
</form>

    </td>
</tr>
@endforeach

</tbody>

        </table>



    </div>

    <div class="mt-3">
    {{ $companies->links() }}
</div>


@endsection
