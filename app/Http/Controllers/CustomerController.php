<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->paginate(10);
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'province' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'country' => 'nullable|string',
            'telephone' => 'nullable|string',
            'hp' => 'nullable|string',
            'hp2' => 'nullable|string',
            'fax' => 'nullable|string',
            'email' => 'nullable|email',
            'tax_name' => 'nullable|string',
            'npw' => 'nullable|string',
            'nppkp' => 'nullable|string',
            'ekspedisi' => 'nullable|string',
            'account_number' => 'nullable|string',
            'information' => 'nullable|string',
        ]);

        Customer::create($validated);

        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil ditambahkan!');
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'province' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'country' => 'nullable|string',
            'telephone' => 'nullable|string',
            'hp' => 'nullable|string',
            'hp2' => 'nullable|string',
            'fax' => 'nullable|string',
            'email' => 'nullable|email',
            'tax_name' => 'nullable|string',
            'npw' => 'nullable|string',
            'nppkp' => 'nullable|string',
            'ekspedisi' => 'nullable|string',
            'account_number' => 'nullable|string',
            'information' => 'nullable|string',
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')->with('success', 'Data pelanggan berhasil diperbarui!');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil dihapus!');
    }
}