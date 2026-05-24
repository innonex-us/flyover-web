<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Services\BkashPaymentService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PaymentReconcileCommandTest extends TestCase
{
    public function test_reconcile_command_invokes_service_for_pending_payments()
    {
        // Ensure DB is migrated
        Artisan::call('migrate', ['--force' => true]);

        // Create a pending payment
        $payment = Payment::create([
            'payable_type' => 'Tests\\Dummy',
            'payable_id' => 0,
            'gateway' => 'bkash',
            'amount' => 100.00,
            'currency' => 'BDT',
            'status' => 'pending',
            'gateway_payment_id' => 'TEST123',
        ]);

        // Make sure the payment is older than the reconcile cutoff
        $payment->created_at = now()->subDays(2);
        $payment->save();

        $mock = $this->createMock(BkashPaymentService::class);
        $mock->expects($this->once())->method('verifyAndSync')->with($this->equalTo($payment->gateway_payment_id));

        $this->app->instance(BkashPaymentService::class, $mock);

        $exit = Artisan::call('payments:reconcile', ['--days' => 0]);

        $this->assertEquals(0, $exit);
    }
}
