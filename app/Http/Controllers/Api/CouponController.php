<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CouponService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CouponController extends Controller
{
    public function __construct(
        private CouponService $couponService
    ) {}

    /**
     * Validate a coupon code via AJAX
     */
    public function validate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50'],
            'service_type' => ['required', 'string', 'in:tours,hotels,transfers,visas'],
            'service_id' => ['nullable', 'integer'],
            'amount' => ['required', 'numeric', 'min:0'],
        ]);

        $result = $this->couponService->validate(
            $validated['code'],
            $validated['service_type'],
            $validated['amount'],
            $validated['service_id'] ?? null,
            auth()->user()
        );

        if ($result['valid']) {
            // Store in session for checkout
            Session::put('applied_coupon', [
                'code' => $result['coupon']->code,
                'coupon_id' => $result['coupon']->id,
                'discount' => $result['discount'],
                'service_type' => $validated['service_type'],
            ]);
        }

        return response()->json([
            'success' => $result['valid'],
            'message' => $result['message'],
            'coupon' => $result['valid'] ? [
                'id' => $result['coupon']->id,
                'code' => $result['coupon']->code,
                'description' => $result['coupon']->description,
                'discount_display' => $result['coupon']->getFormattedDiscount(),
            ] : null,
            'discount' => $result['valid'] ? $result['discount'] : 0,
            'final_amount' => $result['valid'] ? $result['final_amount'] : $validated['amount'],
        ]);
    }

    /**
     * Remove applied coupon from session
     */
    public function remove(): JsonResponse
    {
        Session::forget('applied_coupon');

        return response()->json([
            'success' => true,
            'message' => 'Coupon removed.',
        ]);
    }

    /**
     * Get available coupons for a service
     */
    public function available(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'service_type' => ['required', 'string', 'in:tours,hotels,transfers,visas'],
            'service_id' => ['nullable', 'integer'],
            'amount' => ['required', 'numeric', 'min:0'],
        ]);

        $coupons = $this->couponService->getAvailableCoupons(
            $validated['service_type'],
            $validated['amount'],
            $validated['service_id'] ?? null,
            auth()->user()
        );

        return response()->json([
            'success' => true,
            'coupons' => $coupons,
        ]);
    }
}
