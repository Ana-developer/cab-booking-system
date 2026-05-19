<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    // List + Search
    public function index(Request $request)
    {
        $companies = Company::when($request->search, function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate(5);

        return view('admin.companies.index', compact('companies'));
    }

    // Show create form
    public function create()
    {
        return view('admin.companies.create');
    }

    // Store company
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'owner_name' => 'nullable|string|max:255',
            'business_type' => 'required|in:normal,wedding,both',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('company-logos', 'public');
        }

        Company::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'owner_name' => $request->owner_name,
            'business_type' => $request->business_type,
            'logo' => $logoPath,
        ]);

        return redirect()->route('admin.companies.index')
            ->with('success', 'Company created successfully');
    }

    // Edit
    public function edit($id)
    {
        $company = Company::findOrFail($id);
        return view('admin.companies.edit', compact('company'));
    }

    // Update
    public function update(Request $request, $id)
    {
        $company = Company::findOrFail($id);

        $company->update([
            'name' => $request->name,
            'owner_name' => $request->owner_name,
            'phone' => $request->phone,
        ]);

        return redirect()->route('admin.companies.index')
            ->with('success', 'Company updated successfully');
    }

    // Delete
    public function destroy($id)
    {
        Company::findOrFail($id)->delete();

        return redirect()->route('admin.companies.index')
            ->with('success', 'Company deleted successfully');
    }
}
