<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'color' => 'nullable|string'
        ]);

        $product = Product::findOrFail($request->product_id);
        
        $cart = session()->get('cart', []);
        
        // Create a unique key for product + color combination
        $cartKey = $product->id . '_' . ($request->color ?? 'default');
        
        if(isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $request->quantity;
        } else {
            $cart[$cartKey] = [
                "id" => $product->id,
                "name" => $product->name,
                "quantity" => $request->quantity,
                "price" => $product->price,
                "image" => $product->main_image_path,
                "color" => $request->color
            ];
        }
        
        session()->put('cart', $cart);
        
        if ($request->action === 'buy_now') {
            return redirect()->route('cart')->with('success', 'Product added to cart successfully!');
        }
        
        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'cart_key' => 'required',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = session()->get('cart', []);

        if(isset($cart[$request->cart_key])) {
            $cart[$request->cart_key]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'Cart updated successfully');
        }

        return redirect()->back()->with('error', 'Item not found in cart');
    }

    public function remove(Request $request)
    {
        $request->validate([
            'cart_key' => 'required'
        ]);

        $cart = session()->get('cart', []);

        if(isset($cart[$request->cart_key])) {
            unset($cart[$request->cart_key]);
            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'Item removed from cart');
        }

        return redirect()->back()->with('error', 'Item not found in cart');
    }
}
