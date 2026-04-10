<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ContactMessage;
use App\Models\CustomizationRequest;
use Illuminate\Http\JsonResponse;

class DataExportController extends Controller
{
    /**
     * Get a summary of available data.
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'contacts_count' => ContactMessage::count(),
                'inquiries_count' => CustomizationRequest::count(),
                'bookings_count' => Booking::count(),
            ]
        ]);
    }

    /**
     * Get all contact messages.
     */
    public function contacts(): JsonResponse
    {
        $contacts = ContactMessage::orderBy('created_at', 'desc')->get();
        return response()->json([
            'status' => 'success',
            'data' => $contacts
        ]);
    }

    /**
     * Get all customization requests (inquiries).
     */
    public function inquiries(): JsonResponse
    {
        $inquiries = CustomizationRequest::with('package')->orderBy('created_at', 'desc')->get();
        return response()->json([
            'status' => 'success',
            'data' => $inquiries
        ]);
    }

    /**
     * Get all bookings.
     */
    public function bookings(): JsonResponse
    {
        $bookings = Booking::with('payable')->orderBy('created_at', 'desc')->get();
        return response()->json([
            'status' => 'success',
            'data' => $bookings
        ]);
    }
}
