<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::where('company_id', auth()->user()->company_id)->get();
        return view('admin.vehicles.index', compact('vehicles'));
    }

    public function create()
    {
        return view('admin.vehicles.create');
    }

    public function store(Request $request)
    {
        Vehicle::create([
            'company_id' => auth()->user()->company_id,
            'name' => $request->name,
            'category' => $request->category,
            'number_plate' => $request->number_plate,
            'self_drive_allowed' => $request->self_drive_allowed ?? false,
            'included_km' => $request->included_km,
            'extra_km_price' => $request->extra_km_price,
        ]);

        return redirect()->route('admin.vehicles.index');
    }
}

