<a href="{{ route('vehicles.create') }}">Add Vehicle</a>

@foreach($vehicles as $vehicle)
    <p>{{ $vehicle->name }} - {{ $vehicle->number_plate }}</p>
@endforeach
