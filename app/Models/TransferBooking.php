<?php

namespace App\Models;

use App\Models\Concerns\HasPayments;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferBooking extends Model
{
    use HasFactory, HasPayments;

    protected $fillable = [
        'route_id',
        'is_custom',
        'pickup_location',
        'drop_location',
        'passenger_count',
        'travel_date',
        'pickup_time',
        'user_id',
        'guest_name',
        'guest_email',
        'guest_phone',
        'special_request',
        'status',
        'total_amount',
        'coupon_id',
        'discount_amount',
        'final_amount',
        'payment_status',
        'payment_method',
        'payment_reference',
    ];

    protected $casts = [
        'travel_date' => 'date',
        'is_custom' => 'boolean',
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
    ];

    public function route()
    {
        return $this->belongsTo(TransferRoute::class, 'route_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
