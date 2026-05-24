<?php

namespace App\Services;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class BkashPaymentService
{
    public function createCheckout(Payment $payment): Payment
    {
        $token = $this->grantToken();
        $invoiceNumber = $payment->merchant_invoice_number ?: $this->buildInvoiceNumber($payment);

        $payload = [
            'amount' => (string) number_format((float) $payment->amount, 2, '.', ''),
            'currency' => $payment->currency ?: config('services.bkash.currency', 'BDT'),
            'intent' => 'sale',
            'merchantInvoiceNumber' => $invoiceNumber,
            'callbackURL' => $this->callbackUrl(),
        ];

        $response = Http::baseUrl(config('services.bkash.base_url'))
            ->withHeaders([
                'accept' => 'application/json',
                'content-type' => 'application/json',
                'authorization' => $token,
                'x-app-key' => config('services.bkash.app_key'),
            ])
            ->post('/tokenized/checkout/create', $payload)
            ->throw()
            ->json();

        $payment->update([
            'gateway' => 'bkash',
            'status' => 'initiated',
            'merchant_invoice_number' => $invoiceNumber,
            'gateway_payment_id' => Arr::get($response, 'paymentID'),
            'gateway_checkout_url' => Arr::get($response, 'bkashURL'),
            'request_payload' => $payload,
            'response_payload' => $response,
            'initiated_at' => now(),
        ]);

        if (blank($payment->gateway_checkout_url)) {
            throw new RuntimeException('bKash checkout URL was not returned.');
        }

        return $payment;
    }

    public function complete(string $paymentId): Payment
    {
        $payment = Payment::where('gateway', 'bkash')
            ->where('gateway_payment_id', $paymentId)
            ->firstOrFail();

        $token = $this->grantToken();

        $response = Http::baseUrl(config('services.bkash.base_url'))
            ->withHeaders([
                'accept' => 'application/json',
                'content-type' => 'application/json',
                'authorization' => $token,
                'x-app-key' => config('services.bkash.app_key'),
            ])
            ->post('/tokenized/checkout/execute', ['paymentID' => $paymentId])
            ->throw()
            ->json();

        $payment->update([
            'status' => 'paid',
            'gateway_transaction_id' => Arr::get($response, 'trxID'),
            'response_payload' => $response,
            'executed_at' => now(),
            'completed_at' => now(),
        ]);

        $this->syncPayableState($payment, 'paid', Arr::get($response, 'trxID') ?: $paymentId);

        return $payment;
    }

    public function markFailed(string $paymentId, string $status = 'failed'): Payment
    {
        $payment = Payment::where('gateway', 'bkash')
            ->where('gateway_payment_id', $paymentId)
            ->firstOrFail();

        $payment->update([
            'status' => $status,
            'failed_at' => $status === 'failed' ? now() : $payment->failed_at,
            'cancelled_at' => $status === 'cancelled' ? now() : $payment->cancelled_at,
        ]);

        $this->syncPayableState($payment, $status === 'cancelled' ? 'cancelled' : 'unpaid', $paymentId);

        return $payment;
    }

    /**
     * Query bKash for the given paymentID and return the response array.
     */
    public function verifyTransaction(string $paymentId): array
    {
        $token = $this->grantToken();

        $response = Http::baseUrl(config('services.bkash.base_url'))
            ->withHeaders([
                'accept' => 'application/json',
                'content-type' => 'application/json',
                'authorization' => $token,
                'x-app-key' => config('services.bkash.app_key'),
            ])
            ->post('/tokenized/checkout/query', ['paymentID' => $paymentId])
            ->throw()
            ->json();

        return $response;
    }

    /**
     * Verify transaction from bKash response and update local Payment and payable state.
     */
    public function verifyAndSync(string $paymentId): Payment
    {
        $payment = Payment::where('gateway', 'bkash')
            ->where('gateway_payment_id', $paymentId)
            ->firstOrFail();

        $response = $this->verifyTransaction($paymentId);

        // If transaction already present on response, treat as paid
        $trx = Arr::get($response, 'trxID') ?: Arr::get($response, 'transactionID');

        if ($trx) {
            $payment->update([
                'status' => 'paid',
                'gateway_transaction_id' => $trx,
                'response_payload' => array_merge($payment->response_payload ?? [], $response),
                'completed_at' => now(),
            ]);

            $this->syncPayableState($payment, 'paid', $trx);
            return $payment;
        }

        // Not paid yet
        $payment->update([
            'response_payload' => array_merge($payment->response_payload ?? [], $response),
        ]);

        return $payment;
    }

    public function createFor(Model $payable, float $amount): Payment
    {
        return $payable->payments()->create([
            'gateway' => 'bkash',
            'amount' => $amount,
            'currency' => config('services.bkash.currency', 'BDT'),
            'status' => 'pending',
        ]);
    }

    protected function grantToken(): string
    {
        $response = Http::baseUrl(config('services.bkash.base_url'))
            ->withHeaders([
                'accept' => 'application/json',
                'content-type' => 'application/json',
                'username' => config('services.bkash.username'),
                'password' => config('services.bkash.password'),
            ])
            ->post('/tokenized/checkout/token/grant', [
                'app_key' => config('services.bkash.app_key'),
                'app_secret' => config('services.bkash.app_secret'),
            ])
            ->throw()
            ->json();

        $token = Arr::get($response, 'id_token');

        if (blank($token)) {
            throw new RuntimeException('Unable to obtain a bKash access token.');
        }

        return $token;
    }

    protected function callbackUrl(): string
    {
        return config('services.bkash.callback_url', route('payments.bkash.success'));
    }

    protected function buildInvoiceNumber(Payment $payment): string
    {
        return sprintf('INV-%s-%s', $payment->id, Str::upper(Str::random(8)));
    }

    protected function syncPayableState(Payment $payment, string $status, string $reference): void
    {
        $payable = $payment->payable;

        if (!$payable) {
            return;
        }

        if (property_exists($payable, 'fillable')) {
            $updates = [
                'payment_status' => $status,
                'payment_method' => 'bkash',
                'payment_reference' => $reference,
            ];

            if (array_key_exists('payment_status', $payable->getAttributes()) || $payable->isFillable('payment_status')) {
                $payable->fill($updates)->save();
                return;
            }

            $payable->forceFill($updates)->save();
        }
    }
}