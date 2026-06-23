<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Invalid email address provided.'], 422);
        }

        $email = $request->email;

        // Check if email already claimed a coupon
        $existing = Coupon::where('email', $email)->first();
        if ($existing) {
            return response()->json([
                'success' => true, 
                'message' => 'You already have a coupon!', 
                'code' => $existing->code
            ]);
        }

        // Create new coupon
        $coupon = Coupon::create([
            'email' => $email,
            'code' => 'FIRSTORDER15',
            'discount_percentage' => 15,
            'is_used' => false
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Welcome! Here is your 15% discount code.',
            'code' => $coupon->code
        ]);
    }
}
