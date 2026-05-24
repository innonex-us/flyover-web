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
            $payment = $bkashPaymentService->complete($paymentId);

            return redirect()->to($this->confirmationUrl($payment))->with('success', 'Payment completed successfully.');
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