<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Validate a promo code against current cart subtotal.
     */
    public function validateCoupon(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string'],
            'subtotal' => ['required', 'numeric', 'min:0'],
        ]);

        $code = strtoupper(trim($validated['code']));
        $subtotal = (float) $validated['subtotal'];

        $coupon = Coupon::where('code', $code)
            ->where('is_active', true)
            ->first();

        if (! $coupon) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid or expired promotional code.',
            ], 422);
        }

        if ($coupon->expires_at && $coupon->expires_at->isPast()) {
            return response()->json([
                'valid' => false,
                'message' => 'This promo code has expired.',
            ], 422);
        }

        if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
            return response()->json([
                'valid' => false,
                'message' => 'This promo code has reached its maximum usage limit.',
            ], 422);
        }

        if ($subtotal < $coupon->min_spend) {
            return response()->json([
                'valid' => false,
                'message' => "Minimum spend of RM {$coupon->min_spend} required for this code.",
            ], 422);
        }

        $discount = $coupon->calculateDiscount($subtotal);

        return response()->json([
            'valid' => true,
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => $coupon->value,
            'discount_amount' => $discount,
            'new_subtotal' => max(0, $subtotal - $discount),
            'message' => "Promotional code '{$coupon->code}' applied successfully!",
        ]);
    }
}
