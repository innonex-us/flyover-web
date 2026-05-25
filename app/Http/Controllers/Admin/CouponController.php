<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Hotel;
use App\Models\Package;
use App\Models\TransferRoute;
use App\Models\Visa;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::latest()->paginate(20);
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        $serviceTypes = [
            'all' => 'All Services',
            'tours' => 'Tour Packages',
            'hotels' => 'Hotels',
            'transfers' => 'Pick & Drop',
            'visas' => 'Visa Services',
        ];

        // Get specific services for selective coupons
        $tours = Package::select('id', 'title')->orderBy('title')->get();
        $hotels = Hotel::select('id', 'name')->orderBy('name')->get();
        $transfers = TransferRoute::select('id', 'pickup_location', 'drop_location')->orderBy('pickup_location')->get();
        $visas = Visa::select('id', 'country')->orderBy('country')->get();

        return view('admin.coupons.create', compact(
            'serviceTypes',
            'tours',
            'hotels',
            'transfers',
            'visas'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:coupons', 'regex:/^[A-Z0-9_-]+$/i'],
            'description' => ['nullable', 'string', 'max:255'],
            'type' => ['required', Rule::in(['percentage', 'fixed'])],
            'value' => ['required', 'numeric', 'min:0'],
            'min_order_amount' => ['nullable', 'numeric', 'min:0'],
            'max_discount_amount' => ['nullable', 'numeric', 'min:0'],
            'applies_to' => ['required', Rule::in(['all', 'tours', 'hotels', 'transfers', 'visas'])],
            'applicable_ids' => ['nullable', 'array'],
            'applicable_ids.*' => ['integer'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'usage_limit_per_user' => ['nullable', 'integer', 'min:1'],
            'starts_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after:starts_at'],
            'is_active' => ['boolean'],
        ], [
            'code.regex' => 'Coupon code can only contain letters, numbers, hyphens, and underscores.',
        ]);

        $validated['code'] = strtoupper($validated['code']);
        $validated['is_active'] = $request->boolean('is_active', true);

        // Convert applicable_ids to null if empty
        if (empty($validated['applicable_ids'])) {
            $validated['applicable_ids'] = null;
        }

        Coupon::create($validated);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon created successfully!');
    }

    public function edit(Coupon $coupon)
    {
        $serviceTypes = [
            'all' => 'All Services',
            'tours' => 'Tour Packages',
            'hotels' => 'Hotels',
            'transfers' => 'Pick & Drop',
            'visas' => 'Visa Services',
        ];

        // Get specific services for selective coupons
        $tours = Package::select('id', 'title')->orderBy('title')->get();
        $hotels = Hotel::select('id', 'name')->orderBy('name')->get();
        $transfers = TransferRoute::select('id', 'pickup_location', 'drop_location')->orderBy('pickup_location')->get();
        $visas = Visa::select('id', 'country')->orderBy('country')->get();

        return view('admin.coupons.edit', compact(
            'coupon',
            'serviceTypes',
            'tours',
            'hotels',
            'transfers',
            'visas'
        ));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', Rule::unique('coupons')->ignore($coupon->id), 'regex:/^[A-Z0-9_-]+$/i'],
            'description' => ['nullable', 'string', 'max:255'],
            'type' => ['required', Rule::in(['percentage', 'fixed'])],
            'value' => ['required', 'numeric', 'min:0'],
            'min_order_amount' => ['nullable', 'numeric', 'min:0'],
            'max_discount_amount' => ['nullable', 'numeric', 'min:0'],
            'applies_to' => ['required', Rule::in(['all', 'tours', 'hotels', 'transfers', 'visas'])],
            'applicable_ids' => ['nullable', 'array'],
            'applicable_ids.*' => ['integer'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'usage_limit_per_user' => ['nullable', 'integer', 'min:1'],
            'starts_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after:starts_at'],
            'is_active' => ['boolean'],
        ], [
            'code.regex' => 'Coupon code can only contain letters, numbers, hyphens, and underscores.',
        ]);

        $validated['code'] = strtoupper($validated['code']);
        $validated['is_active'] = $request->boolean('is_active', true);

        // Convert applicable_ids to null if empty
        if (empty($validated['applicable_ids'])) {
            $validated['applicable_ids'] = null;
        }

        $coupon->update($validated);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon updated successfully!');
    }

    public function destroy(Coupon $coupon)
    {
        // Check if coupon has been used
        if ($coupon->usages()->count() > 0) {
            return redirect()->route('admin.coupons.index')
                ->with('error', 'Cannot delete coupon that has already been used.');
        }

        $coupon->delete();

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon deleted successfully!');
    }

    /**
     * Toggle coupon active status
     */
    public function toggleStatus(Coupon $coupon)
    {
        $coupon->update(['is_active' => !$coupon->is_active]);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon status updated successfully!');
    }
}
