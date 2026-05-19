<form method="POST" action="{{ route('vehicles.store') }}">
@csrf

<input name="name" placeholder="Car name">
<input name="number_plate" placeholder="Number plate">

<select name="category">
    <option value="luxury">Luxury</option>
    <option value="sedan">Sedan</option>
</select>

<input type="number" name="included_km" placeholder="Included KM">
<input type="number" name="extra_km_price" placeholder="Extra KM price">

<label>
<input type="checkbox" name="self_drive_allowed"> Self Drive Allowed
</label>

<button type="submit">Save</button>
</form>
