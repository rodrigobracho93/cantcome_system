<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::orderBy('first_name')->get();
        return view('customers.index', compact('customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'document' => 'nullable|string|max:50|unique:customers,document',
            'company' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        $customer = Customer::create($validated);

        if ($request->ajax()) {
            return response()->json($customer);
        }

        return redirect()->route('customers.index')->with('success', 'Cliente registrado exitosamente.');
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $customers = Customer::where('first_name', 'like', "%{$query}%")
            ->orWhere('last_name', 'like', "%{$query}%")
            ->orWhere('document', 'like', "%{$query}%")
            ->orWhere('company', 'like', "%{$query}%")
            ->orderBy('first_name')
            ->limit(10)
            ->get();

        return response()->json($customers);
    }
}
