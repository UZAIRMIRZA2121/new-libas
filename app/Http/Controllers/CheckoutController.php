<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function process(Request $request)
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        $request->validate([
            'email' => 'required|email',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'apartment' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'payment' => 'required|in:cod,card',
        ]);

        $subtotal = collect($cart)->sum(function($item) {
            return $item['price'] * $item['quantity'];
        });

        $shipping = 0; // Free shipping for now
        $appliedCoupon = session('applied_coupon');
        $discountAmount = 0;
        
        if ($appliedCoupon) {
            $coupon = Coupon::where('code', $appliedCoupon['code'])->first();
            if ($coupon && !$coupon->is_used) {
                // Verify email if necessary
                if (!$coupon->email || strcasecmp($coupon->email, $request->email) === 0) {
                    $discountAmount = ($subtotal * $coupon->discount_percentage) / 100;
                    
                    if ($coupon->email) {
                        $coupon->update(['is_used' => true]);
                    }
                }
            }
        }

        $total = $subtotal - $discountAmount + $shipping;

        $order = Order::create([
            'user_id' => auth()->id(),
            'order_number' => 'ORD-' . strtoupper(Str::random(10)),
            'email' => $request->email,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'address' => $request->address,
            'apartment' => $request->apartment,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
            'phone' => $request->phone,
            'payment_method' => $request->payment,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'discount_amount' => $discountAmount,
            'coupon_code' => $appliedCoupon ? $appliedCoupon['code'] : null,
            'total' => $total,
            'status' => 'pending',
        ]);

        foreach ($cart as $item) {
            $order->items()->create([
                'product_id' => $item['id'],
                'product_name' => $item['name'],
                'color' => $item['color'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);

            // Decrement stock
            $product = Product::find($item['id']);
            if ($product && $product->stock >= $item['quantity']) {
                $product->decrement('stock', $item['quantity']);
            }
        }

        session()->forget('cart');
        session()->forget('applied_coupon');

        return redirect()->route('checkout.success', $order->order_number);
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'email' => 'nullable|email'
        ]);

        $coupon = Coupon::where('code', $request->code)->first();

        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'Invalid coupon code.']);
        }

        if ($coupon->is_used) {
            return response()->json(['success' => false, 'message' => 'This coupon has already been used.']);
        }

        if ($coupon->email && strcasecmp($coupon->email, $request->email) !== 0) {
            return response()->json(['success' => false, 'message' => 'This coupon is not valid for your email address.']);
        }

        $cart = session('cart', []);
        if (empty($cart)) {
            return response()->json(['success' => false, 'message' => 'Your cart is empty.']);
        }

        $subtotal = collect($cart)->sum(function($item) {
            return $item['price'] * $item['quantity'];
        });

        $discountAmount = ($subtotal * $coupon->discount_percentage) / 100;

        session(['applied_coupon' => [
            'code' => $coupon->code,
            'percentage' => $coupon->discount_percentage,
            'discount_amount' => $discountAmount
        ]]);

        return response()->json([
            'success' => true,
            'message' => 'Coupon applied successfully!',
            'discount_amount' => $discountAmount,
            'percentage' => $coupon->discount_percentage,
            'new_total' => $subtotal - $discountAmount
        ]);
    }

    public function removeCoupon()
    {
        session()->forget('applied_coupon');
        return response()->json(['success' => true]);
    }

    public function success($order_number)
    {
        $order = Order::where('order_number', $order_number)->with('items')->firstOrFail();
        return view('checkout-success', compact('order'));
    }
}
