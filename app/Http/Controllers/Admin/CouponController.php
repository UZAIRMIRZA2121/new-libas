<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::orderBy('created_at', 'desc')->get();
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        $users = \App\Models\User::all();
        return view('admin.coupons.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'nullable|email',
            'code' => 'required|string|max:50',
            'discount_percentage' => 'required|integer|min:1|max:100',
        ]);

        Coupon::create([
            'email' => $request->email,
            'code' => $request->code,
            'discount_percentage' => $request->discount_percentage,
            'is_used' => $request->has('is_used'),
        ]);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon created successfully.');
    }

    public function edit(Coupon $coupon)
    {
        $users = \App\Models\User::all();
        return view('admin.coupons.edit', compact('coupon', 'users'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'email' => 'nullable|email',
            'code' => 'required|string|max:50',
            'discount_percentage' => 'required|integer|min:1|max:100',
        ]);

        $coupon->update([
            'email' => $request->email,
            'code' => $request->code,
            'discount_percentage' => $request->discount_percentage,
            'is_used' => $request->has('is_used'),
        ]);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon updated successfully.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('admin.coupons.index')->with('success', 'Coupon deleted successfully.');
    }
}
