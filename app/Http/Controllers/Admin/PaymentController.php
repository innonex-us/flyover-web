<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\HotelBooking;
use App\Models\Payment;
use App\Models\TransferBooking;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with('payable')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('gateway')) {
            $query->where('gateway', $request->gateway);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%$search%")
                  ->orWhere('gateway_payment_id', 'like', "%$search%")
                  ->orWhere('merchant_invoice_number', 'like', "%$search%");
            });
        }

        $payments = $query->paginate(20)->withQueryString();

        $totals = [
            'total'     => Payment::sum('amount'),
            'paid'      => Payment::where('status', 'paid')->sum('amount'),
            'pending'   => Payment::where('status', 'pending')->count(),
            'failed'    => Payment::whereIn('status', ['failed', 'cancelled'])->count(),
        ];

        return view('admin.payments.index', compact('payments', 'totals'));
    }

    public function show(Payment $payment)
    {
        match ($payment->payable_type) {
            Booking::class         => $payment->load('payable.payable', 'payable.user'),
            HotelBooking::class    => $payment->load('payable.room.hotel', 'payable.user'),
            TransferBooking::class => $payment->load('payable.route', 'payable.user'),
            default                => $payment->load('payable'),
        };

        return view('admin.payments.show', compact('payment'));
    }
}
