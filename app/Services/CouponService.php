<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CouponService
{
    /**
     * Validate a coupon code
     */
    public function validate(
        string $code,
        string $serviceType,
        float $amount,
        ?int $serviceId = null,
        ?User $user = null
    ): array {
        $user = $user ?? Auth::user();

        $coupon = Coupon::where('code', strtoupper(trim($code)))->first();

        if (!$coupon) {
            return [
                'valid' => false,
                'message' => 'Invalid coupon code.',
            ];
        }

        if (!$coupon->isValid($user)) {
            if ($coupon->expires_at && $coupon->expires_at->isPast()) {
                return [
                    'valid' => false,
                    'message' => 'This coupon has expired.',
                ];
            }

            if ($coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit) {
                return [
                    'valid' => false,
                    'message' => 'This coupon has reached its usage limit.',
                ];
            }

            if ($user && $coupon->usage_limit_per_user !== null) {
                $userUsageCount = $coupon->usages()
                    ->where('user_id', $user->id)
                    ->count();

                if ($userUsageCount >= $coupon->usage_limit_per_user) {
                    return [
                        'valid' => false,
                        'message' => 'You have already used this coupon the maximum number of times.',
                    ];
                }
            }

            return [
                'valid' => false,
                'message' => 'This coupon is not currently valid.',
            ];
        }

        if (!$coupon->appliesTo($serviceType, $serviceId)) {
            $serviceNames = [
                'tours' => 'tour packages',
                'hotels' => 'hotel bookings',
                'transfers' => 'pick & drop services',
                'visas' => 'visa services',
            ];

            return [
                'valid' => false,
                'message' => $coupon->applies_to === 'all'
                    ? 'This coupon is not applicable to this service.'
                    : 'This coupon is only valid for ' . ($serviceNames[$coupon->applies_to] ?? $coupon->applies_to) . '.',
            ];
        }

        if ($coupon->min_order_amount !== null && $amount < $coupon->min_order_amount) {
            return [
                'valid' => false,
                'message' => 'Minimum order amount of ৳' . number_format($coupon->min_order_amount, 0) . ' required.',
            ];
        }

        $discount = $coupon->calculateDiscount($amount);

        if ($discount <= 0) {
            return [
                'valid' => false,
                'message' => 'This coupon cannot be applied to this order.',
            ];
        }

        return [
            'valid' => true,
            'coupon' => $coupon,
            'discount' => $discount,
            'final_amount' => $amount - $discount,
            'message' => 'Coupon applied successfully! You saved ৳' . number_format($discount, 0) . '.',
        ];
    }

    /**
     * Apply coupon to a booking
     */
    public function apply(
        Coupon $coupon,
        $booking,
        ?User $user = null
    ): CouponUsage {
        $user = $user ?? Auth::user();

        $amount = $booking->total_amount;
        $discount = $coupon->calculateDiscount($amount);
        $finalAmount = $amount - $discount;

        // Update booking with discount
        $booking->coupon_id = $coupon->id;
        $booking->discount_amount = $discount;
        $booking->final_amount = $finalAmount;
        $booking->save();

        // Record usage
        $usage = CouponUsage::create([
            'coupon_id' => $coupon->id,
            'user_id' => $user?->id,
            'booking_type' => get_class($booking),
            'booking_id' => $booking->id,
            'discount_amount' => $discount,
            'used_at' => now(),
        ]);

        // Increment coupon usage count
        $coupon->incrementUsage();

        return $usage;
    }

    /**
     * Remove coupon from booking
     */
    public function remove($booking): void
    {
        if ($booking->coupon_id) {
            // Delete usage record
            CouponUsage::where('booking_type', get_class($booking))
                ->where('booking_id', $booking->id)
                ->delete();

            // Reset booking amounts
            $booking->coupon_id = null;
            $booking->discount_amount = 0;
            $booking->final_amount = $booking->total_amount;
            $booking->save();
        }
    }

    /**
     * Get available coupons for a service
     */
    public function getAvailableCoupons(
        string $serviceType,
        float $amount,
        ?int $serviceId = null,
        ?User $user = null
    ): array {
        $user = $user ?? Auth::user();

        $coupons = Coupon::valid()
            ->forService($serviceType)
            ->where(function ($q) use ($amount) {
                $q->whereNull('min_order_amount')
                  ->orWhere('min_order_amount', '<=', $amount);
            })
            ->get();

        $result = [];
        foreach ($coupons as $coupon) {
            // Check if user hasn't exceeded per-user limit
            if ($user && $coupon->usage_limit_per_user !== null) {
                $userUsageCount = $coupon->usages()
                    ->where('user_id', $user->id)
                    ->count();

                if ($userUsageCount >= $coupon->usage_limit_per_user) {
                    continue;
                }
            }

            // Check specific ID applicability
            if ($serviceId !== null && !empty($coupon->applicable_ids)) {
                if (!in_array($serviceId, $coupon->applicable_ids)) {
                    continue;
                }
            }

            $discount = $coupon->calculateDiscount($amount);
            if ($discount > 0) {
                $result[] = [
                    'id' => $coupon->id,
                    'code' => $coupon->code,
                    'description' => $coupon->description,
                    'discount_display' => $coupon->getFormattedDiscount(),
                    'discount_amount' => $discount,
                    'final_amount' => $amount - $discount,
                ];
            }
        }

        return $result;
    }
}
