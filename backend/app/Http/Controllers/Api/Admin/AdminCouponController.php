<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminCouponController extends Controller
{
    public function index(): JsonResponse
    {
        $coupons = Coupon::orderBy('id', 'desc')->get();

        return response()->json([
            'coupons' => $coupons,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:coupons,code'],
            'type' => ['required', 'in:fixed,percentage'],
            'value' => ['required', 'numeric', 'min:0'],
            'min_spend' => ['nullable', 'numeric', 'min:0'],
            'max_discount' => ['nullable', 'numeric', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'expires_at' => ['nullable', 'date'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['code'] = strtoupper(trim($validated['code']));

        $coupon = Coupon::create($validated);

        return response()->json([
            'message' => 'Coupon created successfully',
            'coupon' => $coupon,
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $coupon = Coupon::findOrFail($id);

        return response()->json([
            'coupon' => $coupon,
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $coupon = Coupon::findOrFail($id);

        $validated = $request->validate([
            'code' => ['sometimes', 'required', 'string', 'max:50', 'unique:coupons,code,' . $id],
            'type' => ['sometimes', 'required', 'in:fixed,percentage'],
            'value' => ['sometimes', 'required', 'numeric', 'min:0'],
            'min_spend' => ['nullable', 'numeric', 'min:0'],
            'max_discount' => ['nullable', 'numeric', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'expires_at' => ['nullable', 'date'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if (isset($validated['code'])) {
            $validated['code'] = strtoupper(trim($validated['code']));
        }

        $coupon->update($validated);

        return response()->json([
            'message' => 'Coupon updated successfully',
            'coupon' => $coupon,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        return response()->json([
            'message' => 'Coupon deleted successfully',
        ]);
    }
}
