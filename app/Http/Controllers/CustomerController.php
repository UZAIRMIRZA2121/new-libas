<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function dashboard()
    {
        return redirect()->route('customer.orders');
    }

    public function orders()
    {
        $orders = Order::where('user_id', auth()->id())->latest()->paginate(10);
        return view('customer.orders.index', compact('orders'));
    }

    public function showOrder(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }
        $order->load('items');
        return view('customer.orders.show', compact('order'));
    }

    public function requestRefund(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'refund_reason' => 'required|string|max:1000'
        ]);

        if ($order->status !== 'delivered') {
            return redirect()->back()->with('error', 'Only delivered orders can be refunded.');
        }

        if (!$order->delivered_at || $order->delivered_at->diffInDays(now()) > 7) {
            return redirect()->back()->with('error', 'Refund requests are only valid within 7 days of delivery.');
        }

        $order->update([
            'status' => 'refund_request',
            'refund_reason' => $request->refund_reason
        ]);

        return redirect()->back()->with('success', 'Refund request submitted successfully. We will review it shortly.');
    }
}
