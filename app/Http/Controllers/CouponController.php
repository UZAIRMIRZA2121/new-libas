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

        // Find or create the generic welcome coupon
        $coupon = Coupon::firstOrCreate(
            ['code' => 'FIRSTORDER15'],
            ['discount_percentage' => 15]
        );

        return response()->json([
            'success' => true,
            'message' => 'Welcome! Here is your 15% discount code.',
            'code' => $coupon->code
        ]);
    }
}
