<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'type',
        'value',
        'min_order_amount',
        'max_discount_amount',
        'applies_to',
        'applicable_ids',
        'usage_limit',
        'usage_limit_per_user',
        'used_count',
        'starts_at',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'applicable_ids' => 'array',
        'usage_limit' => 'integer',
        'usage_limit_per_user' => 'integer',
        'used_count' => 'integer',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function usages(): HasMany
    {
        return $this->hasMany(CouponUsage::class);
    }

    /**
     * Check if coupon is valid and applicable
     */
    public function isValid(?User $user = null): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->starts_at && $this->starts_at->isFuture()) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return false;
        }

        if ($user && $this->usage_limit_per_user !== null) {
            $userUsageCount = $this->usages()
                ->where('user_id', $user->id)
                ->count();
            
            if ($userUsageCount >= $this->usage_limit_per_user) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if coupon applies to specific service type
     */
    public function appliesTo(string $serviceType, ?int $serviceId = null): bool
    {
        if ($this->applies_to === 'all') {
            return true;
        }

        if ($this->applies_to !== $serviceType) {
            return false;
        }

        // Check specific IDs if applicable
        if ($serviceId !== null && !empty($this->applicable_ids)) {
            return in_array($serviceId, $this->applicable_ids);
        }

        return true;
    }

    /**
     * Calculate discount amount
     */
    public function calculateDiscount(float $amount): float
    {
        // Check minimum order amount
        if ($this->min_order_amount !== null && $amount < $this->min_order_amount) {
            return 0;
        }

        $discount = 0;

        if ($this->type === 'percentage') {
            $discount = $amount * ($this->value / 100);
        } else {
            $discount = $this->value;
        }

        // Apply max discount limit
        if ($this->max_discount_amount !== null && $discount > $this->max_discount_amount) {
            $discount = $this->max_discount_amount;
        }

        // Don't discount more than the amount
        if ($discount > $amount) {
            $discount = $amount;
        }

        return round($discount, 2);
    }

    /**
     * Format discount display
     */
    public function getFormattedDiscount(): string
    {
        if ($this->type === 'percentage') {
            return $this->value . '% OFF';
        }
        return '৳' . number_format($this->value, 0) . ' OFF';
    }

    /**
     * Increment usage count
     */
    public function incrementUsage(): void
    {
        $this->increment('used_count');
    }

    /**
     * Scope: Active and valid
     */
    public function scopeValid($query)
    {
        return $query
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')
                  ->orWhere('starts_at', '<=', Carbon::now());
            })
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>=', Carbon::now());
            });
    }

    /**
     * Scope: For specific service type
     */
    public function scopeForService($query, string $serviceType)
    {
        return $query->where(function ($q) use ($serviceType) {
            $q->where('applies_to', 'all')
              ->orWhere('applies_to', $serviceType);
        });
    }
}
