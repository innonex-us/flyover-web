<?php

namespace App\Helpers;

use App\Models\Booking;
use App\Models\HotelBooking;
use App\Models\TransferBooking;
use App\Models\Package;
use App\Models\Visa;
use App\Models\Hotel;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SiteStats
{
    public static function get(): array
    {
        return Cache::remember('site_stats', 300, function () {

            // Total unique customers across all booking types
            $tourBookings     = Booking::whereIn('status', ['confirmed', 'completed'])->count();
            $hotelBookings    = HotelBooking::whereIn('status', ['confirmed', 'completed'])->count();
            $transferBookings = TransferBooking::whereIn('status', ['confirmed', 'completed'])->count();
            $totalBookings    = $tourBookings + $hotelBookings + $transferBookings;

            // Offset: admin-configurable base traveller count (to show accumulated history)
            $offset = (int) Setting::get('stat_travellers_offset', 0);
            $totalTravellers = $totalBookings + $offset;

            // Destinations: distinct locations from packages + distinct countries from visas
            $packageLocations = Package::where('is_active', true)
                ->distinct()->pluck('location')
                ->map(fn($l) => strtolower(trim($l)))->unique()->count();
            $visaCountries = Visa::where('is_active', true)
                ->distinct()->pluck('country')
                ->map(fn($c) => strtolower(trim($c)))->unique()->count();
            $destinations = max($packageLocations, $visaCountries, $packageLocations + $visaCountries > 0 ? $packageLocations + $visaCountries : 0);
            // Deduplicate: some package locations overlap with visa countries — take distinct union
            $allDestinations = Package::where('is_active', true)->distinct()->pluck('location')
                ->merge(Visa::where('is_active', true)->distinct()->pluck('country'))
                ->map(fn($d) => strtolower(trim($d)))->unique()->count();
            $destinations = $allDestinations ?: $destinations;

            // Visa approval rate: completed visa bookings ÷ total visa bookings
            $visaTotal     = Booking::where('payable_type', 'App\\Models\\Visa')->count();
            $visaApproved  = Booking::where('payable_type', 'App\\Models\\Visa')
                ->whereIn('status', ['confirmed', 'completed'])->count();
            $visaApprovalRate = $visaTotal > 0
                ? round(($visaApproved / $visaTotal) * 100, 1)
                : (float) Setting::get('stat_visa_approval_rate', 94.2);

            // Star rating — no ratings table yet, use admin-configured value
            $rating = Setting::get('stat_rating', '4.8');

            return [
                'travellers'        => static::formatTravellers($totalTravellers),
                'destinations'      => $destinations ?: (int) Setting::get('stat_destinations', 62),
                'rating'            => $rating,
                'visa_approval'     => $visaApprovalRate,
                'visa_total'        => $visaTotal,
            ];
        });
    }

    private static function formatTravellers(int $n): string
    {
        if ($n >= 1_000_000) {
            return number_format($n / 1_000_000, 1) . 'M+';
        }
        if ($n >= 1_000) {
            return number_format($n / 1_000, 0) . 'K+';
        }
        return $n > 0 ? $n . '+' : Setting::get('stat_travellers_display', '1.2M+');
    }
}
