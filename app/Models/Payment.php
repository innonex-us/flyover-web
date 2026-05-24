<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'public_token',
        'payable_type',
        'payable_id',
        'gateway',
        'amount',
        'currency',
        'status',
        'gateway_payment_id',
        'gateway_transaction_id',
        'gateway_checkout_url',
        'merchant_invoice_number',
        'request_payload',
        'response_payload',
        'initiated_at',
        'executed_at',
        'completed_at',
        'failed_at',
        'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'request_payload' => 'array',
            'response_payload' => 'array',
            'initiated_at' => 'datetime',
            'executed_at' => 'datetime',
            'completed_at' => 'datetime',
            'failed_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $payment): void {
            if (blank($payment->public_token)) {
                $payment->public_token = (string) Str::uuid();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'public_token';
    }

    public function payable()
    {
        return $this->morphTo();
    }
}