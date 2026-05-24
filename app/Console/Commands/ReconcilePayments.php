<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Services\BkashPaymentService;
use Illuminate\Console\Command;

class ReconcilePayments extends Command
{
    protected $signature = 'payments:reconcile {--days=7 : Only reconcile payments older than this many days}';

    protected $description = 'Reconcile pending/initiated payments with the gateway.';

    public function handle(BkashPaymentService $bkash): int
    {
        $days = (int) $this->option('days');

        $this->info("Finding payments older than {$days} days with status pending/initiated...");

        $cutoff = now()->subDays($days);

        $payments = Payment::query()
            ->where('gateway', 'bkash')
            ->whereIn('status', ['pending', 'initiated'])
            ->where('created_at', '<', $cutoff)
            ->get();

        $this->info('Found: ' . $payments->count());

        foreach ($payments as $payment) {
            $this->line("Reconciling payment {$payment->id} ({$payment->gateway_payment_id})...");

            try {
                $bkash->verifyAndSync($payment->gateway_payment_id);
                $this->info(' -> reconciled');
            } catch (\Throwable $e) {
                report($e);
                $this->error(' -> failed: ' . $e->getMessage());
            }
        }

        $this->info('Done.');

        return self::SUCCESS;
    }
}
