<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'customer');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $customers = $query->latest()->paginate(10);
        return view('admin.customers.index', compact('customers'));
    }

    public function edit(User $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, User $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'apartment' => 'nullable|string',
            'city' => 'nullable|string',
            'postal_code' => 'nullable|string',
        ]);

        $customer->update($request->only([
            'name', 'email', 'phone', 'address', 'apartment', 'city', 'postal_code'
        ]));

        return redirect()->route('admin.customers.index')->with('success', 'Customer updated successfully.');
    }

    public function orders(User $customer)
    {
        $orders = Order::where('user_id', $customer->id)->latest()->paginate(10);
        return view('admin.customers.orders', compact('customer', 'orders'));
    }
}

