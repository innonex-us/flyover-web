<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\HotelBooking;
use App\Models\Payment;
use App\Models\TransferBooking;
use App\Services\BkashPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class PaymentController extends Controller
{
    public function show(Payment $payment)
    {
        if ($payment->status === 'paid') {
            return redirect()->to($this->confirmationUrl($payment))->with('success', 'Payment already completed.');
        }

        match ($payment->payable_type) {
            Booking::class        => $payment->load('payable.payable'),
            HotelBooking::class   => $payment->load('payable.room.hotel'),
            TransferBooking::class => $payment->load('payable.route'),
            default               => $payment->load('payable'),
        };

        return view('payments.checkout', compact('payment'));
    }

    public function start(Request $request, Payment $payment, BkashPaymentService $bkashPaymentService)
    {
        if ($payment->status === 'paid') {
            return redirect()->to($this->confirmationUrl($payment))->with('success', 'Payment is already completed.');
        }

        try {
            $payment = $bkashPaymentService->createCheckout($payment->fresh(['payable']));

            if (blank($payment->gateway_checkout_url)) {
                return redirect()->to($this->confirmationUrl($payment))->with('error', 'Unable to start bKash checkout right now.');
            }

            return redirect()->away($payment->gateway_checkout_url);
        } catch (\Throwable $throwable) {
            report($throwable);

            return redirect()->to($this->confirmationUrl($payment))->with('error', 'Unable to start bKash checkout right now. Please try again.');
        }
    }

    public function success(Request $request, BkashPaymentService $bkashPaymentService)
    {
        $paymentId = $request->input('paymentID', $request->input('payment_id'));

        if (blank($paymentId)) {
            return redirect()->route('home')->with('error', 'Missing bKash payment reference.');
        }

        try {
            // Use server-side verification rather than assuming the frontend completed the flow.
            $payment = $bkashPaymentService->verifyAndSync($paymentId);

            if ($payment->status === 'paid') {
                return redirect()->to($this->confirmationUrl($payment))->with('success', 'Payment completed successfully.');
            }

            return redirect()->route('home')->with('error', 'We could not verify the bKash payment.');
        } catch (\Throwable $throwable) {
            report($throwable);

            return redirect()->route('home')->with('error', 'We could not verify the bKash payment.');
        }
    }

    public function fail(Request $request, BkashPaymentService $bkashPaymentService)
    {
        return $this->handleNonSuccess($request, $bkashPaymentService, 'failed', 'Payment failed.');
    }

    public function cancel(Request $request, BkashPaymentService $bkashPaymentService)
    {
        return $this->handleNonSuccess($request, $bkashPaymentService, 'cancelled', 'Payment cancelled.');
    }

    protected function handleNonSuccess(Request $request, BkashPaymentService $bkashPaymentService, string $status, string $message)
    {
        $paymentId = $request->input('paymentID', $request->input('payment_id'));

        if (filled($paymentId)) {
            try {
                $payment = $bkashPaymentService->markFailed($paymentId, $status);

                return redirect()->to($this->confirmationUrl($payment))->with('error', $message);
            } catch (\Throwable $throwable) {
                report($throwable);
            }
        }

        return redirect()->route('home')->with('error', $message);
    }

    /**
     * Endpoint for server-to-server webhook notifications from bKash.
     * Expects middleware to verify signature and optional IP allowlist.
     */
    public function webhook(Request $request, BkashPaymentService $bkashPaymentService)
    {
        $payload = $request->all();
        $paymentId = $payload['paymentID'] ?? $payload['payment_id'] ?? $request->input('paymentID');

        if (blank($paymentId)) {
            return response()->json(['error' => 'missing payment id'], 400);
        }

        // Find Payment
        $payment = Payment::where('gateway', 'bkash')
            ->where('gateway_payment_id', $paymentId)
            ->first();

        if (!$payment) {
            // unknown payment - log and return 404 to caller
            report(new \RuntimeException("bkash webhook for unknown payment: {$paymentId}"));
            return response()->json(['error' => 'unknown payment'], 404);
        }

        // Idempotency: if already paid, acknowledge
        if ($payment->status === 'paid') {
            return response()->json(['status' => 'ok'], 200);
        }

        try {
            $payment = $bkashPaymentService->verifyAndSync($paymentId);

            return response()->json(['status' => 'ok'], 200);
        } catch (\Throwable $throwable) {
            report($throwable);
            return response()->json(['error' => 'verification failed'], 500);
        }
    }

    protected function confirmationUrl(Payment $payment): string
    {
        return match ($payment->payable_type) {
            Booking::class => URL::signedRoute('bookings.confirmation', $payment->payable),
            HotelBooking::class => route('hotels.confirmation', $payment->payable),
            TransferBooking::class => route('transfers.confirmation', $payment->payable),
            default => route('home'),
        };
    }
}