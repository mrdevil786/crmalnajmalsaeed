<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomersController extends Controller
{
    // RETRIEVE ALL CUSTOMERS AND DISPLAY THEM IN A VIEW
    public function index()
    {
        $customers = Customer::all();
        return view('admin.customers.index', compact('customers'));
    }

    // VALIDATE AND STORE A NEW CUSTOMER
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
        ]);

        $customer = new Customer();
        $customer->name = $request->name;
        $customer->email = $request->email;

        $customer->save();

        return redirect()->route('admin.customers.index')->with('success', 'Customer registered successfully!');
    }

    // SHOW A FORM FOR EDITING THE SPECIFIED CUSTOMER
    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('admin.customers.edit', compact('customer'));
    }

    // VALIDATE AND UPDATE THE SPECIFIED CUSTOMER
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $id,
        ]);

        $customer = Customer::findOrFail($id);
        $customer->name = $request->name;
        $customer->email = $request->email;

        $customer->save();

        return redirect()->route('admin.customers.index')->with('success', 'Customer updated successfully!');
    }

    // DELETE THE SPECIFIED CUSTOMER
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return redirect()->route('admin.customers.index')->with('success', 'Customer deleted successfully!');
    }
}