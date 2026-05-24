<?php

namespace App\Models\Concerns;

use App\Models\Payment;

trait HasPayments
{
    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    public function latestPayment()
    {
        return $this->morphOne(Payment::class, 'payable')->latestOfMany();
    }
}