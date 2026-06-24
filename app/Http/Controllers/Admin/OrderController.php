<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::latest()->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items');
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,delivered,completed,refund_request,refunded'
        ]);

        $data = ['status' => $request->status];
        
        // If status is changed to delivered, mark the time
        if ($request->status == 'delivered' && !$order->delivered_at) {
            $data['delivered_at'] = now();
        }

        $order->update($data);

        return redirect()->back()->with('success', 'Order status updated successfully.');
    }

    public function print(Order $order)
    {
        $order->load('items');
        return view('admin.orders.print', compact('order'));
    }

    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|in:unpaid,paid,failed,refunded'
        ]);

        $order->update(['payment_status' => $request->payment_status]);

        return redirect()->back()->with('success', 'Payment status updated successfully.');
    }
}
