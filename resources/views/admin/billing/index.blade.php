@extends('admin.layout')

@section('content')

<div class="container mt-4">
    <h4 class="mb-4">Billing List</h4>


    <div class="card">
   <div class="d-flex justify-content-between align-items-center mb-3">

    <form method="GET" class="d-flex align-items-center">

        <label class="me-2">Show</label>

        <select name="per_page"
                class="form-select form-select-sm"
                style="width:80px;"
                onchange="this.form.submit()">

            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
        </select>

        <label class="ms-2">entries</label>

        {{-- Keep search value --}}
        <input type="hidden" name="search" value="{{ request('search') }}">

    </form>

    {{-- Search Box --}}
    <form method="GET" class="d-flex">
        <input type="text"
               name="search"
               class="form-control form-control-sm me-2"
               placeholder="Search ID, Customer, Car..."
               value="{{ request('search') }}"
               style="width:250px;">

        <input type="hidden" name="per_page" value="{{ request('per_page') }}">

        <button class="btn btn-dark btn-sm">Search</button>
    </form>

</div>

<div id="billingTable">
    @include('admin.bookings.partials.billing-table')

    
</div>




       



        </div>
    </div>
</div>



<script>
document.addEventListener("DOMContentLoaded", function () {

    let searchInput = document.querySelector("input[name='search']");
    let timeout = null;

    searchInput.addEventListener("keyup", function () {

        clearTimeout(timeout);

        timeout = setTimeout(function () {

            let search = searchInput.value;
let perPage = document.querySelector("select[name='per_page']").value;

fetch(`?search=${search}&per_page=${perPage}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(data => {
                document.getElementById("billingTable").innerHTML = data;
            });

        }, 500); // delay for typing

    });

});
</script>

<script>
document.addEventListener("click", function (e) {

    if (e.target.classList.contains('markPaidBtn')) {

        let billingId = e.target.getAttribute('data-id');

        Swal.fire({
            title: 'Are you sure?',
            text: "Mark this bill as paid?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, mark as paid'
        }).then((result) => {

            if (result.isConfirmed) {

                let form = document.createElement('form');
                form.method = 'POST';
                form.action = '/billing/' + billingId + '/mark-paid';

                let csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';

                form.appendChild(csrf);

                document.body.appendChild(form);
                form.submit();
            }

        });

    }

});
</script>


@endsection